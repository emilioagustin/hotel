<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentTest extends TestCase
{
    private $content, $contentImage;
    private $contentTranslations;
    private $languages = ['ca', 'es', 'en', 'fr'];

    private function getFakePayload($name=NULL, $status=NULL, $order=NULL, $languages=NULL)
    {
        $this->content = factory(\App\Content::class)->create();
        
        $payload = [
            'name' => (isset($name)) ? $name : $this->content->name,
            'status' => ($status) ? $status : $this->content->status,
            'order' => ($order) ? $order : $this->content->order,
            'languages' => ($languages) ? $this->contentTranslations : []
        ];
        return $payload;
    }

    private function getFakePayloadWithId($id, $name=NULL, $status=NULL, $order=NULL, $languages=NULL)
    {
        $payload = $this->getFakePayload($name, $status, $order, $languages);
        array_unshift($payload, array($id => $id));
        return $payload;
    }

    private function getFakeImagePayload()
    {
        $this->contentImage = factory(\App\ContentImage::class)->create();
        
        $payload = [
            'content_id' => $this->contentImage->content_id,
            'image_path' => $this->contentImage->image_path,
        ];
        return $payload;
    }

    private function setFactoryLanguages() 
    {
        $this->contentTranslations = [];
        foreach ($this->languages as $language) 
        {
            $contentTranslation = factory(\App\ContentTranslation::class)->create();
            $contentTranslation->language = $language;
            $this->contentTranslations[$language] = $contentTranslation;
        }
    }

    public function testsContentsAreCreatedCorrectly()
    {
        $payload = $this->getFakePayload();

        $response = $this->json('POST', '/content/', $payload);
        $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => $this->content->name
        ]);
    }

    public function testsContentLanguagesAreCreatedCorrectly()
    {
        $this->setFactoryLanguages(NULL, NULL, NULL, []);
        $payload = $this->getFakePayload();
        $response = $this->json('POST', '/content/', $payload);

        foreach ($this->languages as $language) 
        {
            $contentTranslation = new \App\ContentTranslation();
            $contentTranslation->id = 0;
            $contentTranslation->language = 'ca';
            $contentTranslation->title = '';
            $contentTranslation->body = '';
            $this->assertTrue($contentTranslation->is($response->original['languages'][$language]));
        }
    }

    public function testsContentsRequiresName()
    {
        $payload = $this->getFakePayload('');

        $this->json('POST', '/content/', $payload)
            ->assertStatus(500);

        $payload = $this->getFakePayload($this->content->name);

        $this->json('POST', '/content/', $payload)
            ->assertStatus(200);
    }

    public function testsContentsAreUpdatedCorrectly()
    {
        $payload = $this->getFakePayload();

        $this->json('POST', '/content/', $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->content->name
            ]);

        $payload = $this->getFakePayloadWithId($this->content->id, 'updateTest');
        
        $this->json('PUT', '/content/update/'.$this->content->id, $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'updateTest'
            ]);       
    }

    public function testsContentLanguagesAreUpdatedCorrectly()
    {
        $this->setFactoryLanguages(NULL, NULL, NULL, []);
        $payload = $this->getFakePayload();

        $this->json('POST', '/content/', $payload);

        $this->contentTranslations['ca']->content_id = $this->content->id;
        $this->contentTranslations['ca']->title = 'Test title';
        $this->contentTranslations['ca']->body = 'Test body';

        $payload = $this->getFakePayloadWithId($this->content->id, $this->content->name, NULL, NULL, 'languages');  

        $response = $this->json('PUT', '/content/update/'.$this->content->id, $payload);

        foreach ($this->contentTranslations as $language => $contentTranslation) 
        {
            $this->assertTrue($contentTranslation->is($response->original['languages'][$language]));
        }
    }

    public function testsUpdateContentNotFound()
    {
        $payload = $this->getFakePayloadWithId(100);
        
        $this->json('PUT', '/content/update/100', $payload)
            ->assertStatus(404);
    }

    public function testsDeleteContentCorrectly()
    {
        $payload = $this->getFakePayload();
        
        $this->json('DELETE', '/content/'.$this->content->id)
            ->assertStatus(200);
    }

    public function testsDeleteContentNotFound()
    {
        $payload = $this->getFakePayloadWithId(100);
        
        $this->json('DELETE', '/content/100')
            ->assertStatus(404);
    }

    public function testsDeleteContentImageCorrectly()
    {
        $payload = $this->getFakeImagePayload();
        $this->json('DELETE', '/content/images/'.$this->contentImage->id)
            ->assertStatus(200);
    }

    public function testsDeleteContentImageNotFound()
    {        
        $this->json('DELETE', '/content/images/1500')
            ->assertStatus(404);
    }
}

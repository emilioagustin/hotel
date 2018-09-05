<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Content;
use App\ContentTranslation;
use App\ContentImage;
use Image;

class ContentController extends Controller
{
    public function index()
    {
        return Content::all()->sortBy('order')->values()->all();
    }
 
    public function show($id)
    { 
        $language_codes = ['ca', 'es', 'en', 'fr'];
        $content = Content::findOrFail($id);
        $translations = Content::find($id)->Translations()->get();
        $languages = [];
        if (count($translations) > 0) {
            foreach ($translations as $translation)
            {
                $languages[$translation->language] = $translation;
            }
        }
        $database_languages = $translations->map(function ($translation, $key) {
            return $translation->language;
        })->toArray();

        $intersect_languages = array_diff($language_codes, $database_languages);
        if (count($intersect_languages) > 0) {
            foreach ($intersect_languages as $intersect_language)
            {
                $translation = new ContentTranslation();
                $translation->id = 0;
                $translation->language = $intersect_language;
                $translation->title = '';
                $translation->body = '';
                $languages[$intersect_language] = $translation;
            }
        }
        
        return [
            'id'            => $content->id, 
            'name'          => $content->name,
            'status'        => $content->status, 
            'languages'     => $languages
        ];
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();
        $content = new Content();
        $content->name = $data['name'];
        $content->status = $data['status'];
        $content->order = Content::max('order') + 1;
        $content->save(); 
        $languages = $data['languages'];
        if (count($languages) > 0) {
            foreach ($languages as $language)
            {
                $translation = new ContentTranslation();
                $translation->content_id =  $content->id;
                $translation->language =  $language['language'];
                $translation->title =  ($language['title'] != null) ? $language['title'] : '';
                $translation->body =  ($language['body'] != null) ? $language['body'] : '';
                $translation->save();    
            }
        }
            return $this->show($content->id);
    }

    public function updateContent(Request $request, $id)
    {
        $data = $request->json()->all();
        $content = Content::findOrFail($id);
        $content->update($data); 
        $languages = $data['languages'];
        if (count($languages) > 0) {
            foreach ($languages as $language)
            {
                $translation = ContentTranslation::find($language['id']);
                if (!$translation) {
                    $translation = new ContentTranslation();
                }
                $translation->content_id =  $id;
                $translation->language =  $language['language'];
                $translation->title =  ($language['title'] != null) ? $language['title'] : '';
                $translation->body =  ($language['body'] != null) ? $language['body'] : '';
                $translation->save();    
            }
        }
        return $this->show($id);
    }

    public function updateStatus(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $content->update($request->all());
        return $this->index();
    }

    public function updateMassive(Request $request)
    {
        $data = $request->json()->all();
        $rows = $data['rows'];
        foreach ($rows as $row)
        {
            $content = Content::findOrFail($row['id']);
            $content->update($row);     
        }
        return $this->index();
    }

    public function delete(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $translations = ContentTranslation::all()->where('content_id', $id)->all();
        if (count($translations) > 0) 
        {
            foreach ($translations as $translation) 
            {
                $translation->delete();
            }
        }
        $images = ContentImage::all()->where('content_id', $id)->all();
        if (count($images) > 0) 
        {
            foreach ($images as $image) 
            {
                $image->delete();
            }
        }
        $content->delete();
        return $this->index();
    }

    public function images($id)
    {
        $content = Content::findOrFail($id);
        return ContentImage::all()->where('content_id', $id)->all();
    }

    public function uploadImages(Request $request, $id)
    {
        $content = Content::findOrFail($id);
        $this->validate($request, [
            'items' => 'required',
            'items.*' => 'mimes:bmp,jpg,jpeg,png,gif'
        ]);
        if($request->hasfile('items'))
        {
            $path = public_path()."/img/$id/";
            if (!file_exists($path)) 
            {
                mkdir($path);
            }
            if (count($request->file('items')) > 0) {
                foreach($request->file('items') as $file)
                {
                    $name =  uniqid('', true).'.'.$file->getClientOriginalExtension();
                    $file->move($path, $name);
                    $image_path = $path.$name;
                    $image = Image::make($image_path);
                    $image->resize($image->width() < 1024 ? $image->width() : 1024, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save();
                    $content_image = new ContentImage();
                    $content_image->content_id = $id;
                    $content_image->image_path = $name;
                    $content_image->save(); 
                }
            }
        }
        return $this->images($id);        
    }

    public function deleteImage(Request $request, $id)
    {
        $image = ContentImage::findOrFail($id);
        $image->delete();
        return $this->images($image->content_id);
    }
}

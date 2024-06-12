<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\HeaderUtils;

class AttachmentController extends Controller
{


	public function index(Request $request){

		$request->validate([
			'item_type' => 'required|string',
			'item_id' => 'required|numeric',
		]);

		$item_type = $request->get('item_type');
		$item_id = $request->get('item_id');

		return response()->json([
			'items' => Attachment::belongsToItem($item_type, $item_id)->get()
		]);

	}

	public function store(Request $request){

        $request->validate([
			'name' => 'nullable|string',
			'item_type' => 'required|string',
			'item_id' => 'nullable|numeric',
		]);

		$name = $request->get('name');

		$file = $request->file('file');

		if(empty($name)){
			$name = $file->getClientOriginalName();
		}

		if(empty($file)){
			throw new \Exception('upload failed');
		}

		$itemType = $request->get('item_type');
		$itemIdPath = $itemId = $request->get('item_id');
		if(empty($itemId)){
			$itemId = 0;
			try{
				$itemClass = Attachment::ITEM_CLASSES[$itemType];
				if($itemClass){
					$itemIdPath = $itemClass::max('id') + 1;
				}
			} catch (\Exception $e){
				$itemIdPath = date('Ymd');
			}
		}

		$attachment = new Attachment([
			'name' => $name,
			'file' => '',
			'item_type' => $itemType,
			'item_id' => $itemId
		]);

		$attachment->save();

		$path = $attachment->item_type . '/' . $itemIdPath;

		$filename = Storage::put($path, $file);
		$attachment->file = $filename;

		$attachment->save();

		return [
			'item' => $attachment
		];

	}

	public function update(Attachment $attachment, Request $request){

        $request->validate([
			'name' => 'nullable|string',
		]);

		$name = $request->get('name');

		if(empty($name)){
			$name = File::basename($attachment->file);
		}

		$attachment->name = $name;
		$attachment->save();

		return [
			'item' => $attachment
		];
	}




	/**
	 * @param Attachment $attachment
	 */
	public function download(Attachment $attachment){

        if(in_array($attachment->ext, ['pdf', 'jpeg', 'jpg', 'png', 'bmp'])){

            return Storage::download($attachment->file, $attachment->name, ['Content-Disposition' =>
                HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_INLINE, $attachment->name,'document')
            ]);

        } else {

            return Storage::download($attachment->file, $attachment->name, ['Content-Disposition' =>
                HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_ATTACHMENT, $attachment->name,'document')
            ]);
        }
	}


	/**
	 * @param Attachment $attachment
	 * @param Request $request
	 * @return mixed
	 */
	public function thumb(Attachment $attachment, Request $request){

        $request->validate([
			'w' => 'nullable|numeric',
			'h' => 'nullable|numeric',
		]);

		$w = $request->get('w', 140);
		$h = $request->get('h', null);

		if( in_array($attachment->ext, ['jpeg', 'jpg', 'png', 'bmp', 'tiff'])){
			$image = Image::make(
                Storage::path($attachment->file)
            )->fit($w, $h);
			return $image->response();
		} else {
			throw new \Exception('not image');
		};

	}

	/**
	 * @param Attachment $attachment
	 * @throws \Exception
	 */
	public function destroy(Attachment $attachment){
		$attachment->delete();
		return 'ok';
	}

}

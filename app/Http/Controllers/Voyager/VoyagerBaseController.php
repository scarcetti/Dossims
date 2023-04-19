<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use TCG\Voyager\Events\BreadDataDeleted;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController as BaseVoyagerBaseController;

class VoyagerBaseController extends BaseVoyagerBaseController
{
    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // --- C U S T O M ---

        if($this->check_password_($request->password, $dataType)) {
            $msg = [
                'message'    => 'Incorrect password.',
                'alert-type' => 'error',
            ];

            return redirect()->route("voyager.{$dataType->slug}.index")->with($msg);
        }

        // --- C U S T O M ---

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }

        $affected = 0;

        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            // Check permission
            $this->authorize('delete', $data);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                $this->cleanup($dataType, $data);
            }

            $res = $data->delete();

            if ($res) {
                $affected++;

                event(new BreadDataDeleted($dataType, $data));
            }
        }

        $displayName = $affected > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

        $data = $affected
            ? [
                'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }

        function check_password_($password, $dataType)
        {
            $cred = [ 'id' => Auth::user()->id, 'password' => $password ];

            if(!Auth::guard('web')->attempt($cred)) {
                // return true if password is incorrect
                return true;
            }
        }
}

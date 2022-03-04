<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteDeckJoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $deckJoinRequest = $this->route('deckJoinRequest');
        $user = auth()->user();
        $deckRole = $user->getDeckInfo($deckJoinRequest->deck->id)['role'];

        //If doesnt have the required role, return 403 error code
        return $deckRole === "owner" || $deckRole === "admin" || $user->isOwner();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

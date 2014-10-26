<?php

namespace Grimm\Controller;

use Grimm\Models\MailingList;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use Input;

class ApiController extends \Controller {

    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var Redirect
     */
    private $redirect;

    public function __construct(Factory $validator, Redirector $redirect)
    {
        $this->validator = $validator;
        $this->redirect = $redirect;
    }

    public function overview()
    {
        return \View::make('api.overview');
    }

    public function addToMailingList()
    {
        $validator = $this->validator->make(Input::only([
            'email', 'email_confirmation', 'recaptcha_response_field'
        ]), [
            'email'                    => 'required|email|confirmed|unique:mailing_list',
            'recaptcha_response_field' => 'required|recaptcha'
        ]);

        if ($validator->fails())
        {
            return $this->redirect->to(\URL::to('api'))->withErrors($validator)->withInput(Input::only('email'));
        }

        MailingList::create(Input::only('email'));

        return $this->redirect->to(\URL::to('api'))->with('success', true);
    }

} 
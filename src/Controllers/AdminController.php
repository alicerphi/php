<?php

namespace ZWorkshop\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use ZWorkshop\Models\ImageModel;
use ZWorkshop\Models\ProfileModel;
use ZWorkshop\Services\EmotionService;

class AdminController
{
    const IMAGE_UPLOAD_DIR =  __DIR__ . "/../../web/images/";

    /**
     * Index action
     *
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function index(Application $app, Request $request)
    {
        $username = $app['security.token_storage']->getToken()->getUser();
        $message = $request->get('message');

        // get user details
        $profileModel = new ProfileModel($app['pdo.connection']);
        $userDetails = $profileModel->get($username);

        // get user images
        //TODO: e7 - list images

        return $app['twig']->render('admin.html.twig', [
            'title'                => 'Admin Panel',
            'username'             => $username,
            'firstName'            => $userDetails['FirstName'],
            'lastName'             => $userDetails['LastName'],
            'email'                => $userDetails['Email'],
            'gender'               => $userDetails['Gender'],
            'programmingLanguages' => explode('|', $userDetails['ProgramingLanguages']),
            'description'          => $userDetails['Description'],
            #'images'               => $userImages,
            'message'              => $message,
        ]);
    }

    /**
     * Save profile action
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveProfile(Application $app, Request $request)
    {

        /** @var \PDO $dbConnection */
        $username = $app['security.token_storage']->getToken()->getUser();

        if (isset($request->request)) {

            // get params form post request
            $firstName = $request->get('frist_name');
            $lastName = $request->get('last_name');
            $email = $request->get('email');
            $gender = $request->get('gender');
            $userDescription = $request->get('user_description');
            $programmingLanguages = implode('|', $request->get('programming_languages'));

            $profileModel = new ProfileModel($app['pdo.connection']);

            try {

                $result = $profileModel->save(
                    $username,
                    $firstName,
                    $lastName,
                    $email,
                    $gender,
                    $programmingLanguages,
                    $userDescription
                );

                // check for updated rows
                $message = $result ? 'Successfully updated user data!' : 'User data was not changed!';

            } catch (\Exception $e) {
                $message = 'An error occurred, the data was not updated! ';
            }
        }

        // redirect with a message
        $redirectUrl = '/admin?message=' . $message;

        return $app->redirect($redirectUrl);
    }

    /**
     * Save image action
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveImage(Application $app, Request $request)
    {
        $dbConnection = $app['pdo.connection'];

        $username = $app['security.token_storage']->getToken()->getUser()->getUsername();
        $profileModel = new ProfileModel($dbConnection);
        $profile = $profileModel->get($username);


        // upload file
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        if (is_null($file)) {
            $message = 'No file uploaded!';
        } else {

            //TODO: e5 - upload file, save file details in db
        }

        //TODO: e6 - add feedback messages on upload image

        return $app->redirect('/admin');
    }

    /**
     * Delete image action
     *
     * @param Application $app
     * @param Request $request
     * @param $imageId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImage(Application $app, Request $request, $imageId)
    {

        /** @var \PDO $dbConnection */
        $dbConnection = $app['pdo.connection'];

        //TODO: e9 - delete image
    }
}

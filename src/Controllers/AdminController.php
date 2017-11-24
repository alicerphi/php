<?php

namespace ZWorkshop\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZWorkshop\Models\ImageModel;
use ZWorkshop\Models\ProfileModel;
use ZWorkshop\Services\EmotionService;

/**
 * The admin controller.
 */
class AdminController extends BaseController
{
    private const IMAGE_UPLOAD_DIR =  __DIR__.'/../../web/images/';

    /**
     * The homepage.
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function index(Application $app, Request $request): Response
    {
        $username = $app['security.token_storage']->getToken()->getUser();

        // Get user details.
        $profileModel = new ProfileModel($app['pdo.connection']);
        $userDetails = $profileModel->get($username);

        // Get user images.
        //TODO: e7 - list images

        // Get message from session and delete it afterwards.
        $session = $request->getSession();
        $message = $session->get('message');
        $session->remove('message');

        return $this->render($app, 'admin.html.twig', [
            'title' => 'Admin Panel',
            'username' => $username,
            'firstName' => $userDetails['FirstName'],
            'lastName' => $userDetails['LastName'],
            'email' => $userDetails['Email'],
            //TODO: e2 - add gender field
            'programmingLanguages' => explode('|', $userDetails['ProgramingLanguages']),
            'description' => $userDetails['Description'],
            'images' => [],
            'message' => $message,
        ]);
    }

    /**
     * The save profile action.
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return RedirectResponse
     */
    public function saveProfile(Application $app, Request $request): RedirectResponse
    {
        $username = $app['security.token_storage']->getToken()->getUser();
        if (Request::METHOD_POST === $request->getMethod()) {
            // Get params from post request.
            $firstName = $request->get('first_name');
            $lastName = $request->get('last_name');
            $email = $request->get('email');
            //TODO: e2 - add gender field
            //TODO: e3 - fix user description + add feedback messages
            $userDescription = '';
            $programmingLanguages = implode('|', $request->get('programming_languages'));

            $profileModel = new ProfileModel($app['pdo.connection']);
            try {
                $result = $profileModel->save(
                    $username,
                    $firstName,
                    $lastName,
                    $email,
                    $programmingLanguages,
                    $userDescription
                );

                // Check for updated rows.
                //TODO: e3 - fix user description + add feedback messages
            } catch (\Throwable $e) {
                //TODO: e3 - fix user description + add feedback messages
            }
        }

        // Redirect with a message.
        $redirectUrl = '/admin';
        //TODO: e3 - fix user description + add feedback messages

        return $app->redirect($redirectUrl);
    }

    /**
     * The save image action.
     *
     * @param Application $app
     * @param Request     $request
     *
     * @return RedirectResponse
     */
    public function saveImage(Application $app, Request $request): RedirectResponse
    {
        $username = $app['security.token_storage']->getToken()->getUser()->getUsername();
        $profileModel = new ProfileModel($app['pdo.connection']);
        $profile = $profileModel->get($username);

        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        if (is_null($file)) {
            //TODO: e6 - add feedback messages on upload image
        } else {
            //TODO: e5 - upload file, save file details in db
        }

        // Redirect with a message.
        $redirectUrl = '/admin';
        //TODO: e6 - add feedback messages on upload image

        return $app->redirect($redirectUrl);
    }

    /**
     * The delete image action.
     *
     * @param Application $app
     * @param Request     $request
     * @param int         $imageId
     *
     * @return RedirectResponse
     */
    public function deleteImage(Application $app, Request $request, int $imageId): RedirectResponse
    {
        //TODO: e9 - delete image
    }
}

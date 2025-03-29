<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

	/**
     * @Route("/signup")
     */
	public function indexAction()
	{
	}

	/**
     * @Route("/signup/get/{id}", methods={"GET", "POST"})
	 * @param int $id default 0
     */
    public function getAction($id=0)
    {
        echo $id ;
    }

	// signup/register
	public function registerAction()
	{

		$user = new Users();

		// Store and check for errors
		$success = $user->save(
			$this->request->getPost(),
			array('name', 'email')
		);

		if ($success) {
			echo "Thanks for registering!";
		} else {
			echo "Sorry, the following problems were generated: <br/>";
			foreach ($user->getMessages() as $message) {
				echo $message->getMessage(), "<br/>";
			}
		}

		$this->view->disable();
	}

}

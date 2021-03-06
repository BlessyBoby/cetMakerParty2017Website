<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User_Authentication extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    // Load facebook library
    $this->load->library('facebook-api-php-codexworld/Facebook');

    //Load user model
    $this->load->model('User');
    $this->load->model('Art');
    //Load PartyData model
    $this->load->model('PartyData');
    $this->load->model('MainConfig');
    $this->load->model('Leadership');
    $this->load->model('Roles');
    $this->load->model('ArtistInvitesModel');
  }

  public function logout()
  {
    // Remove local Facebook session
    $this->facebook->destroy_session();
    // Remove user data from session
    $this->session->unset_userdata('token');
    $this->session->unset_userdata('userData');
    // Redirect to login page
    redirect('/user_authentication');
  }

  public function index()
  {
    if($this->MainConfig->checkConfigExists() == 1)
    {
      // $userData = array();
      $data['model_obj'] = $this->User;

      // Include the google api php libraries
      include_once APPPATH."libraries/google-api-php-client/src/Google_Client.php";
      include_once APPPATH."libraries/google-api-php-client/src/contrib/Google_Oauth2Service.php";

      // Google Project API Credentials
      $clientId = '749534622098-otgdlseopgn73sl4kli7r5q855vg7ie6.apps.googleusercontent.com';
      $clientSecret = 'efvIZ9KGEI-Pl9abb5wza-FC';
      $redirectUrl = base_url() . 'user_authentication';

      // Google Client Configuration
      $gClient = new Google_Client();
      $gClient->setApplicationName('CET Maker Party');
      $gClient->setClientId($clientId);
      $gClient->setClientSecret($clientSecret);
      $gClient->setRedirectUri($redirectUrl);
      $google_oauthV2 = new Google_Oauth2Service($gClient);

      // Check if user is logged in
      if($this->facebook->is_authenticated())
      {
        // Get user facebook profile details
        $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture');
        // Preparing data for database insertion
        // $userData['oauth_provider'] = 'facebook';
        // $userData['oauth_uid'] = $userProfile['id'];
        $userData['first_name'] = $userProfile['first_name'];
        $userData['last_name'] = $userProfile['last_name'];
        $userData['email'] = $userProfile['email'];
        // $userData['gender'] = $userProfile['gender'];
        // $userData['locale'] = $userProfile['locale'];
        // $userData['profile_url'] = 'https://www.facebook.com/'.$userProfile['id'];
        // $userData['picture_url'] = $userProfile['picture']['data']['url'];

        // Insert or update user data
        $userID = $this->User->checkUser($userData);
        $userData['id'] = $userID;

        if(!empty($userID))
        {
          // $data['stream'] = $this->User->returnStream($userID);
          // $data['branch'] = $this->User->returnBranch($userID);
          // $data['semester'] = $this->User->returnSemester($userID);
          // $data['mobilenumber'] = $this->User->returnMobileNumber($userID);
        }
        // if($this->PartyData->checkPartyExists() == 0)
        // {
        //
        // }
        // else
        // {
        //   if($this->Leadership->checkAdminExists() == 0)
        //   {
        //     $this->Leadership->insertAdmin($userID);
        //   }
        // }

        // Check user data insert or update status
        if(!empty($userID))
        {
          $data['userData'] = $userData;
          $this->session->set_userdata('userData',$userData);
        }
        else
        {
          $data['userData'] = array();
        }
        // Get logout URL
        $data['logoutUrl'] = $this->facebook->logout_url();
      }
      else
      {
        if (isset($_REQUEST['code']))
        {
          $gClient->authenticate();
          $this->session->set_userdata('token', $gClient->getAccessToken());
          redirect($redirectUrl);
        }
        $token = $this->session->userdata('token');

        if (!empty($token))
        {
          $gClient->setAccessToken($token);
          $userProfile = $google_oauthV2->userinfo->get();
          // Preparing data for session insertion
          // $userData['oauth_provider'] = 'google';
          // $userData['oauth_uid'] = $userProfile['id'];
          $userData['first_name'] = $userProfile['given_name'];
          $userData['last_name'] = $userProfile['family_name'];
          $userData['email'] = $userProfile['email'];
          // $userData['profile_url'] = $userProfile['link'];
          // $checkEmailExist = $this->User->checkEmailExist($userData['email']);
          // if($checkEmailExist == '1f')
          // {
          //   //logout();
          //   redirect(usefacebook);
          // }
          // else
          // {
          // Insert or update user data
          $userID = $this->User->checkUser($userData);
          // if($this->Leadership->checkAdminExists() == 0)
          // {
          //   $this->Leadership->insertAdmin($userID);
          // }
          $userData['id'] = $userID;
          //get level current level information -- start
          if(!empty($userID))
          {
            // $data['stream'] = $this->User->returnStream($userID);
            // $data['branch'] = $this->User->returnBranch($userID);
            // $data['semester'] = $this->User->returnSemester($userID);
            // $data['mobilenumber'] = $this->User->returnMobileNumber($userID);
          }
          //get level current level information -- end
          $this->session->set_userdata('userData',$userData);
          // }
        }

        if ($gClient->getAccessToken())
        {
          $userProfile = $google_oauthV2->userinfo->get();
          // Preparing data for database insertion
          // $userData['oauth_provider'] = 'google';
          // $userData['oauth_uid'] = $userProfile['id'];
          $userData['first_name'] = $userProfile['given_name'];
          $userData['last_name'] = $userProfile['family_name'];
          $userData['email'] = $userProfile['email'];
          // $userData['profile_url'] = $userProfile['link'];
          // Insert or update user data
          $userID = $this->User->checkUser($userData);
          $userData['id'] = $userID;
          // if($this->Leadership->checkAdminExists() == 0)
          // {
          //   $this->Leadership->insertAdmin($userID);
          // }
          //get level current level information -- start
          if(!empty($userID))
          {
            // $data['stream'] = $this->User->returnStream($userID);
            // $data['branch'] = $this->User->returnBranch($userID);
            // $data['semester'] = $this->User->returnSemester($userID);
            // $data['mobilenumber'] = $this->User->returnMobileNumber($userID);
          }
          //get level current level information -- end
          if(!empty($userID))
          {
            $data['userData'] = $userData;
            $this->session->set_userdata('userData',$userData);
          }
          else
          {
            $data['userData'] = array();
          }
        }
        else
        {
          $data['authUrlg'] = $gClient->createAuthUrl();
        }

        $fbuser = '';

        // Get login URL
        $data['authUrlf'] =  $this->facebook->login_url();
      }

      $this->load->view('templates/header');
      $this->load->view('templates/henosis');
      $this->load->view('templates/contentStart');
      if(!empty($this->session->userdata['userData']['id']))
      {
        $this->load->view('templates/headerRow');
        if($this->User->returnCheckValueAdditional() != 1)
        {
          redirect(base_url()."more_details");
        }
        if($this->ArtistInvitesModel->checkIfArtInviteWaiting($this->User->returnEmail($this->session->userdata['userData']['id'])) == 1)
        {
          redirect(base_url()."artistInvites");
        }
        if($this->Leadership->checkIfAdmin($this->session->userdata['userData']['id'], $this->Roles->getAdminRoles($this->PartyData->getCurrentPartyID())) == 1)
        {
          redirect(base_url()."admin");
        }
      }
      // Load login & profile view
      $this->load->view('user_authentication/index',$data);
      $this->load->view('templates/contentEnd');
      require_once 'required/menu.php';
      $this->load->view('templates/footer');
    }
    else
    {
      $this->load->view('errors/not_configured');
    }
  }
}

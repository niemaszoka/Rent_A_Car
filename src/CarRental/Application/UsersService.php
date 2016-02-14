<?php

namespace CarRental\Application;

class UsersService
{
	private $dataService;

    public function __construct($dataService)
    {
        $this->dataService = $dataService;
    }

	public function getAllUsers(){	
		return $this->dataService->getData('users');
	}

	public function getUser($userEmail){
		$allUsers = $this->getAllUsers();

        foreach ($allUsers as $key => $user)
        {
            if ($allUsers[$key]['email'] === $userEmail)
            {
                return $allUsers[$key];
            }
        }

        return null;
	}

	public function addUser($data){
		$id = $this->createUserId();
		$user = array(
				'id' => $this->createUserId(),
				'email' => $data->getEmail(),
				'firstName' => $data->getFirstName(),
				'lastName' => $data->getLastName(),
				'rents' => array()
		);

		$allUsers = $this->getAllUsers();
		array_push($allUsers, $user);
		$this->saveUsers($allUsers);
	}

	public function createUserId(){
		$allUsers = $this->getAllUsers();
		$lastId = end($allUsers)['id'];
		return $lastId + 1;
	}

	public function saveUsers($users){
		$data = array('users' => $users);
		$this->dataService->saveData('users', $data);		
	}

	public function saveUserData($userData){
		$allUsers = $this->getAllUsers();

        foreach ($allUsers as $key => $user)
        {
            if ($allUsers[$key]['email'] === $userData['email'])
            {
                $allUsers[$key] = $userData;
            }
        }

        $this->saveUsers($allUsers);
	}

	public function addCarRental($user, $carId){
		$date = date("Y-m");
		
		if(!array_key_exists($date, $user['rents'])){
			$user['rents'][$date] = 1;
		}else{
			$user['rents'][$date] += 1;
		}

		$this->saveUserData($user);
	}

	public function rentCar($data){

        if( !$this->userExists($data->getEmail()) ){
            $this->addUser($data);
        }

		$userData = $this->getUser($data->getEmail());
        $this->addCarRental($userData, $data->getCarId());
	}

	public function userExists($email){
		if( $this->getUser($email) === null ){
			return false;
		}

		return true;
	}
}
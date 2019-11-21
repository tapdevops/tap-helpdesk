<?php 

namespace App;
use Illuminate\Database\Eloquent\Model;

class LDAP extends Model 
{
	
	public function login( $username, $password, $id )
	{
		//echo $username.'|'.$password.'|'.$id;die();
		$SearchFor = $username;
		$SearchField = "samaccountname";
		$ldapport = 389;
		
		$LDAPHost = "ldap.tap-agri.com";
		$dn = "OU=B.Triputra Agro Persada, DC=tap, DC=corp";
		$LDAPUserDomain = "@tap";

		//$user = $request->iuser;
        //$pass = $request->ipass;
		$LDAPUser = $username;
		$LDAPUserPassword = $password;
		$LDAPFieldsToFind = array("cn", "givenname","company", "samaccountname", "homedirectory", "telephonenumber", "mail");
		
		$cnx = ldap_connect($LDAPHost, $ldapport) or  $info = "Koneksi LDAP Gagal";
		if ( $cnx ) 
		{
			ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);
			$bind = @ldap_bind( $cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword );
			if ( !$bind ) {
				//$response['message'] = 'Username/Password salah';
				//return $response;
				//return redirect('monitoring-workshop-login/'.$id)->with('status', 'Username/Password salah');
				$info = array('count'=>0);
				return $info;
				
				exit();
			}
		}

		$filter = "($SearchField=$SearchFor*)";
		$sr = ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
		$info = ldap_get_entries($cnx, $sr);
		//echo "<pre>"; print_r($info); die();
		return $info;
	}

	public function dologin(Request $request, $id=null) 
	{
		//echo phpinfo(); die();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;

        $param = array(
            "username"=> $username,
            "password"=> $password,
        );
		
		
        $service = API::exec(array(
            'request' => 'POST',
            'host' => 'ldap',
            'method' => 'login',
            'data' => $param
        ));
        $data = $service;
		
		//$sql = " SELECT * FROM tr_user WHERE 1=1 ";
    	//$data = DB::SELECT($sql);
		
		//$data = DB::table('tr_user')->where(['username'=> $username, 'status'=>1, 'password'=>$password])->first();
		//echo "<pre>"; print_r($data); die();
		/*
			[id] => 1
			[username] => caca.karta
			[password] => 123
			[fullname] => Caca Karta
			[email] => caca.karta@emp.com
			[status] => 1
		*/
		
        //if(!empty($data->status)) 
		if($data->username == $username AND Hash::check($password, $data->password) )
		{
			/*
            Session::put('authenticated', time());
            Session::put('username', $username);
			Session::put('fullname', $data->fullname);
			Session::put('username', $username);
			Session::put('role', 'GUDANG');
			Session::put('role_id', 4);
			*/
			
			
            $service = API::exec(array(
                'request' => 'GET',
                'method' => "tr_user_profile/" . $username
            ));
            $profile = $service->data;    
            
			if($profile) 
			{
                Session::put('user_id', $profile[0]->id);
                Session::put('name', $profile[0]->nama);
                Session::put('role', $profile[0]->role_name);
                Session::put('role_id', $profile[0]->role_id);
            } 
			else 
			{
                Session::put('name', $username);
                Session::put('role', 'GUEST');
            }
			
            AccessRight::grantAccess();
            
			echo "<pre>"; print_r(session()->all()); die();
			
			return redirect('/');

        }
		else
		{
            $errors = new MessageBag([
                'password' => ['Email and/or password invalid.']
            ]);
            
            return Redirect::back()->withErrors($errors)->withInput(Input::except('password'));
        }
    }

	public function auth( $username,$password )
	{
		$SearchFor = $username;
		$SearchField = "samaccountname";
		$ldapport = 389;
		
		$LDAPHost = "ldap.tap-agri.com";
		$dn = "OU=B.Triputra Agro Persada, DC=tap, DC=corp";
		$LDAPUserDomain = "@tap";

		
		$LDAPUser = $username;
		$LDAPUserPassword = $password;
		$LDAPFieldsToFind = array("cn", "givenname","company", "samaccountname", "homedirectory", "telephonenumber", "mail");
		
		$cnx = ldap_connect($LDAPHost, $ldapport) or  $info = "Koneksi LDAP Gagal";
		if ( $cnx ) {
			ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);
			$bind = @ldap_bind( $cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword );
			if ( !$bind ) {
				$response['message'] = 'Username/Password salah';
				return $response;
				exit();
			}
		}

		$filter = "($SearchField=$SearchFor*)";
		$sr = ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
		$info = ldap_get_entries($cnx, $sr);

		if ( $info['count'] > 0 ) {
			//$response['status'] = true;
			//$response['message'] = 'Login berhasil';
		}
		else
		{
			
		}

		

	}
	
}
?>
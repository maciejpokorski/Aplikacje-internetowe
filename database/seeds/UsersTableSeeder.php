<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
         $this->staticUserInsert("Maciek", "maciekpokorski@gmail.com", "maciek");        
        }catch(PDOException $e){
            printf ('Maciek user already exists ');
        }
        //factory(App\User::class, 20)->create();
    }

    public static function staticUserInsert($name, $email, $password){
        $user = new App\User;
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->remember_token = str_random(10);
        $user->save();
        return $user;
    }
}

#  Laravel Package that help your to manage your users images such as avatars and covers

- ##  Requirements 
    - PHP 7.0 or higher
    - Laravel 5.6.x or higher
- ##  Installation
  
  Just run a : 
  
  ```composer require oza/user-images-manager```
  
- With Laravel 5.6.x, thanks to Laravel discovering system
 the ServiceProvider is automatically added to your Providers 
- ##  Architecture
 The data is represented in an array containing two fields.
  A "current" field that contains the currently used image
  and another "other" field that contains all the images
  that have been used.
  
  **Eg**:
  ```
    array:2 [
      "current" => {#601
        +"id": "66a5ebbd-645b-4323-9792-281d6942d09c"
        +"src": "http://lorempicsum.com/futurama/255/200/210"
        +"set_at": "2018-04-29"
      }
      "others" => array:3 [
        0 => {#606
          +"id": "9cdfd187-470c-45f6-a558-387d3a8fe0e7"
          +"src": "http://lorempicsum.com/futurama/255/200/2"
          +"set_at": "2018-04-28"
        }
        1 => {#603
          +"id": "eba74758-9721-44e8-8278-e09c237016f7"
          +"src": "http://lorempicsum.com/futurama/255/200/5"
          +"set_at": "2018-04-28"
        }
      
      ]
    ]
  
  ```     
- ##  How to Use
    - Just Add the `UsersImagesManager` trait to your Profile Model.
    ```php
    <?php
  
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Oza\UserImagesManager\Traits\UserImagesManager;
    
    /**
     * App\Profile
     *
     * @mixin \Eloquent
     */
    class Profile extends Model
    {
        use UserImagesManager;
    
        protected $guarded = [];
        protected $table = "profiles";
    }

    ```
    - In your config folder, Open `Profile.php` and config your Profile table and options:
    ```php
    <?php
    
    return [
         /*
         |--------------------------------------------------------------------------
         | USER ID FIELD
         |--------------------------------------------------------------------------
         | this value is the field that contains the user_id
         |
         */
       
         "user_id_field" =>  'user_id',
     
         /*
         |--------------------------------------------------------------------------
         | AVATAR FIELD
         |--------------------------------------------------------------------------
         | this value represent the field that contains avatars json
         |
         */
       
         "avatars_field" => "avatars",
       
         /*
         |--------------------------------------------------------------------------
         | AVATAR FIELD
         |--------------------------------------------------------------------------
         | this value represent the field that contains covers json
         |
         */
       
         "covers_field" => "covers",
       
         /*
         |--------------------------------------------------------------------------
         | Default Images domain
         |--------------------------------------------------------------------------
         | The default images api domain
         |
         */
       
         "default_images_domain" => "https://source.unsplash.com/",
     
         /*
         |--------------------------------------------------------------------------
         | Default Avatar Size
         |--------------------------------------------------------------------------
         | The default avatars size
         |
         */
           
         "default_avatars_size" => "400x400",
       
         /*
         |--------------------------------------------------------------------------
         | Default Cover Size
         |--------------------------------------------------------------------------
         | The default cover size
         |
         */
       
         "default_cover_size" => "1000x800",
     

        
    ];
    ```
    - After theses steps, you can call `AvatarManager or CoverManager etc...`.
    
        - :bangbang: :warning: :boom: The Profile Model must be a user's profile not only The Model Class,
        that means it must have a user_id fields etc...
        
    **Eg:**    
    ```php
    <?php 
    
    class ProfileController extends \App\Http\Controllers\Controller 
     {
      
       public function index(\Illuminate\Http\Request $request) {  
         
         /*-------------------------------------------------------
          | Retrieve User and Profile
          |--------------------------------------------------------
          | retrieve the user and with Relationships get his profile
          | 
          |
          */
       
         $user = App\User::find($request->get('user_id'));
         $profile = $user->profile();
          
          // Then you can get avatarManager Like this
          $manager = $profile->avatarManager(); // return an instance of Avatar
          $manager->getAvatar(); // return the current Avatar
          
          // Or get CoverManager
          $coverManager = $profile->coverManager();
          $cover = $coverManager->getCover();
     }
    }
    ``` 
    - ##  Available Manager 
        - ### Avatar Manager
        Return an Instance of Avatar Class
        
        ```php 
           $profile()->avatarManager(); 
        ```
        - ### Cover Manager
        Return an Instance of Cover Class
                
         ```php 
           $profile()->coverManager(); 
         ```
    
    - ##  Available Model for each Manager
        - ### Current 
        Return a current image for a Manager 
        ```php
          $profile->avatarManager()->current(); // return a string
        ```
        - ### Current In Array format 
        Return an array for current image
        ```php
          $profile->avatarManager()->currentInArray(); // return an array
          
        ```
        **Output:**
        ```
        array:3 [
          "id" => "9da1cdb0-0ef6-4558-aa9c-116950e445d4"
          "src" => "https://source.unsplash.com/random/400x400"
          "set_at" => "2018-04-29"
        ]
        ```
        - ### All 
        Return All images for a Manager 
        ```php
          $profile->avatarManager()->all(); // return an array
        ```
        - ### Others 
        Return All images for a Manager 
        ```php
          $profile->avatarManager()->all(); // return an array
        ```
        - ### Set 
        Put an image to database: it take the image link (string) 
        ```php
          $profile->avatarManager()->set("http://lorempicsum.com/random/100"); // return the current avatar
        ```
        - ### Set by Id
        Put an image that has already been used as an avatar, cover, etc.
        ```php
          /*-------------------------------------------------------
          |  First image
          |--------------------------------------------------------
          | put the first image to database
          */
          
          $profile->avatarManager()->set("http://lorempicsum.com/random/100");
          
          // After that, we retrieve the current image id
          $id = $profile->avatarManager()->currentInArray()['id'];
          
          /*-------------------------------------------------------
          | AND WE PUT ANOTHER IMAGE
          |--------------------------------------------------------
          |
          */
          
          $profile->avatarManager()->set("http://loremimage.com/80");
          
          /*-------------------------------------------------------
          | PUTTING OLD IMAGE AS THE CURRENT AVATAR
          |--------------------------------------------------------
          | After that, the current avatar is the last record.
          | with $id above, I can now tell him to put the image corresponding
          |  to this identifier as the current avatar.
          |  This can be useful if for example when the user wants to 
          |  change his avatar he is shown all the images that
          |  had chosen it before and he has the choice 
          |  to put an old image or a new image
          */
          
          $profile->avatarManager()->setById($id); // return the current avatar
        ```
         - ### Set A Random Image
         You can use it to set a default Image when user is registered. 
         ```php
          $profile->avatarManager()->setRandom(); // return the current avatar
         ```
         
         - All Methods are in MethodsInterface
         ```php
         <?php
   
         namespace Oza\UserImagesManager\Interfaces;
         
         
         interface MethodsInterface
         {
             /**
              * Get the current  object
              *
              * @return mixed
              */
             public function current() : string;
         
             /**
              * @return array
              */
             public function currentInArray(): array;
         
             /**
              * Get all
              *
              * @return array
              */
             public function all(): array;
         
             /**
              * Get Others
              *
              * @return array
              *
              */
             public function others(): array;
         
             /**
              *  Fill All Array and return an instance
              *
              * @return MethodsInterface
              */
             public function getAll(): MethodsInterface;
         
             /**
              * set to profile
              *
              * @param string $src
              * @return string|null
              */
             public function set(string $src): string;
         
             /**
              * Set Random image
              *
              * @return string
              */
             public function setRandom() : string;
             /**
              * Set by an id
              *
              * @param string $id
              * @return mixed
              */
             public function setById(string $id): ?string;
         
             /**
              * @param string $id
              * @return bool
              */
             public function remove(string $id) : bool;
         
             /**
              * @return bool
              */
                   
             public function removeAll() : bool;
         }
         ```
         
    - Add a Custom Manager 
    Just create a class that extends to Manager Class and implements MethodsInterface
    
    ```php
    <?php 
  
    class MyCustomManager extends \Oza\UserImagesManager\Manager implements \Oza\UserImagesManager\Interfaces\MethodsInterface
    {
      
      use \Oza\UserImagesManager\Traits\Methods;
      private $field;
      private $defaultSize;
    
        /**
         * My constructor
         *
         */  
      public function __construct()
      {
            parent::__construct();
            $this->field = config("profile.YOUR_FIELD_IN_PROFILE_TABLE") ?? 'YOUR_FIELD_IN_PROFILE_TABLE';
            $this->defaultSize = config("profile.YOUR_DEFAULT_IMAGE_SIZE") ?? '400x400';  
      }
      
      // Add your methods here
    
    }
    ``` 
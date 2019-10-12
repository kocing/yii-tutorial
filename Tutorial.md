# Yii 1.1 Tutorial for beginners and Programmers

Yii 1.1 has actually reached its end of life. However its still used in many `php` applications. Yii is a great
`MVC` framework. This tutorial looks at working with the different components of `Yii`.

Learn more [here](https://www.yiiframework.com/doc/guide/1.0/en/quickstart.what-is-yii)

# Installation

To install yii head to there download page [here](https://www.yiiframework.com/download) and download the application.

[yii_fig_download.png]

[Comment][Download via git]
You can also download this application via github. Using the download link or following the instructions from the download page.

```bash
git clone git@github.com:yiisoft/yii.git yii
```
[/Comment]

Once you have downloaded the application you can run the command to install a new app.
First extract the `zip` file and then head to the `yii/framework` folder. Then run the following command to create your
app

```bash
php yiic webapp ~Documents/yiiapp
```

The `~Document/yiiapp` is the location where your app will be installed.

[yii_fig_installation_bash.png]

> However you create your application make sure that `yii` is in the **base root** of your newly installed application.
That means you make need to copy across if necessary.

# Running the webserver

Assuming you have xampp installed lets proceed. If you need xampp check this link [here](https://www.apachefriends.org/index.html).

Lets create a new `virutal host` entry in our `httpd-vhosts.conf` file.

```html
<VirtualHost *:80>
    DocumentRoot "/home/shady/Documents/tutorials/yii-tutorial"
    ServerName dev.yiiapp.com
    ServerAlias dev.yiiapp.com
    <Directory "/home/shady/Documents/tutorials/yii-tutorial"> 
        AllowOverride All
        Require all Granted
    </Directory>
</VirtualHost>
```

You will need to change the `DocumentRoot`, `ServerName` and `Directory` locations to suit you.
Now head to your `Servername` mines is `dev.yiiapp.com` and you should see your welcome page.

> Be sure to update your `hosts` file to add your new local website

[yii_fig_welcome_page.png]

# Additional Configurations

In order to get our site working correctly we need to do some more things. First lets create an
`.htaccess` file in the base directory and add 

```text
RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# otherwise forward it to index.php
RewriteRule . index.php
```

This will remove the need to have `index.php` from the url. Next lets update the `urlManager`. Head to
`config/main.php` and uncomment the  `urlManager` change nothing else.

```php
'urlManager'=>array(
    'urlFormat'=>'path',
    'rules'=>array(
        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    ),
),
```


# Hello World

Lets create a basic hello world application. Lets head to the `SiteController.php` file located in
`proected/controllers/SiteController.php`. We add a new function

```php
public function actionHelloWorld(){
    echo "hello world";
}
```

In `yii` public functions with a `prefix` **action** will be accessible via `url`. So lets go to this url at
`dev.yiiapp.com/site/helloworld`. Controllers prefix names add to the `site` path.

[yii_helloworld.png]

# Directory Structure

Lets look at the directory structure and some of the folders to take note of

```text
/yii-tutorial
 /assets - handles caching
 /images 
 /css
 /protected - files in here not access via url
   /commands - console access
   /config - configuration folder
    main.php - main configuration file
   /controllers 
    SiteController.php - default controller
   /models
   /views - holds our view templates
   /extensions - holds any external php packages
   /runtime - holds logs
 /yii - the framework (required)
```

# Routing and Urls

So to create different routes and urls you need to work with controllers. We have one already called `SiteController.php`.
So lets create two more urls in `SiteController.php`.

```php
public function actionHelp(){
 echo "Help me";
}

public function actionFaq(){
 echo "This is a faq";
}
```

We can access these urls via `dev.yiiapp/site/help` and `dev.yiiapp/site/faq`.

To create a new `base` path instead of using `site` you need to create a new controller.
Lets create one called `HomeController.php` and add the following code

```php
<?php

class HomeController extends Controller
{

    public function actionIndex(){
        echo "this is the home";
    }
}
```

Now we have a new `base` path called `dev.yiiapp/home` and the `pathname` is `index`.

[yii_fig_home_url.png]

The `actionIndex` function is the default location for any controller. So you dont need to specify the `pathname`.
You can access `index` via `dev.yiiapp/home` instead of `dev.yiiapp/home/index`.

Lets add another `path` called `features`.

```php
public function actionFeatures(){
    echo "this is the features page";
}
```

[yii_fig_home_url.png]

# Controllers

Controllers are where we render views and get data from our web pages. This is where our web requests are managed.
Controllers are `classes` in yii. We created the `HomeController` above. Lets create another controller called
dashboard.

Create a file in the `protected/controllers` follder called `DashboardController.php`. 

```php
<?php

class DashboardController extends Controller
{

}
```
All controller classes need to extend `Controller`. You can find the controller class in 
the `protected/components/Controller.php`.

Lets add a `home` path to the controller.

```php
<?php

class DashboardController extends Controller
{

    public function actionHome(){
        echo 'home is here';
    }

}
```

Because controllers are classes we can add class variables. Below we add the `pageTitle` to the class.

```php
<?php

class DashboardController extends Controller
{

    public $pageTitle = 'This is the page title';

    public function actionHome(){
        echo 'home is here ' . $this->pageTitle;
    }
}
```

[yii_fig_dashboard_home_url.png]

We can also create normal functions (functions without the **action** prefix) in our controllers. Lets create a message
function.

```php
<?php

class DashboardController extends Controller
{

    public $pageTitle = 'This is the page title';

    public function actionHome(){
        echo 'home is here ' . $this->pageTitle . $this->message("yes or no");
    }
    
    public function message( $msg ){
        return $msg;
    }
}
```

We can call the `message` function using `$this->messsage()` passing in any required attributes.


## Rendering Views

To render views we need to create a folder for our controller with the same name as our controller in the 
`protected/views` directory. For the `Dashboard` controller we create a folder called dashboard. 
Inside the `dashboard` folder we create a file called `view.php`.

[yii_fig_dashboard_view_folder.png]

Inside the `protected/views/dashboard/view.php` file we add

```php
<?php
 echo 'hello to this is the dashboard view';
?>
```

Now in our `DashboardController.php` file we use the `actionHome` function and render our `view.php` file from the 
`views` directory.

```php
public function actionHome(){
    $this->render('view');
}
```

The function we use to render views is `$this->render()`. We can pass in the name of the view file as an parameter.
Our output is a big different but that will be explained later.

[yii_fig_dashboard_home_view.png]

## Functions

We can use functions from our controller in our views once they are `public` or `protected`. Lets add some `html` tags 
to our `view.php` file first.

```html
<h1>Home Page</h1>
<h2>Home Page description</h2>
<p>message</p>
```

Now we can access the `message` function from our `DashboardController.php`.

```php
<h1>Home Page</h1>
<h2>Home Page description</h2>
<p><?php echo $this->message("this is a message...");?></p>
```

[yii_fig_dashboard_function_in_view.png]

## Public variables

We can access our `public` class variables from our views as well. Lets modify the `view.php` file to add the
`pageTitle` variable.

```php
<h1><?php echo $this->pageTitle;?></h1>
<h2>Home Page description</h2>
<p><?php echo $this->message("this is a message...");?></p>
```


## Passing data from the controller

We can pass data from our controller to our views. In the `DashboardController.php` lets edit the
`actionHome` function.

```php
public function actionHome(){
    $emails = ['test@gmail.com','johndoe@gmail.com'];
    $this->render('view',['emails'=>$emails]);
}
```

The `render` function second argument allows for us to pass an key and value array with any data we choose.
Lets use the data we passed in the `view.php` file. Add the code below.

```php
<h1><?php echo $this->pageTitle;?></h1>
<h2>Home Page description</h2>
<p><?php echo $this->message("this is a message...");?></p>
<ul>
<?php foreach($emails as $email):?>
    <li><?php echo $email;?></li>
<?php endforeach;?>
</ul>
```

We use a `foreach` loop on our `$emails` data that we passed in from our controller to output a list of emails.

[yii_fig_controllers_passing_data.png]

# Layouts

In `yii` we can use layouts. Layouts are used exact as it seems. You can create default layouts and pass in content
to them. Layouts is a advance topic so lets look at the basics.

In the `protected/views/layouts` directory you have your default layouts you can create more as you deem necessary.
Open the `column1.php` file you will see

```php
<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>
```

The `$this->beginContent("//layouts/main")` pulls in the `protected/views/layouts/main.php` file.
The `$content` is whatever view we are using is passed using the `$content` variable.

In the `protected/components/Controller.php` you will see at the top of the file

```php
public $layout='//layouts/column1';
```

This is the default layout every controller then inherits this layout. Lets create a new layout.

Lets create a file in the layouts folder and call it `basic.php`.

In side lets add a basic html template.

```php
<!DOCTYPE html>
<html>
<title><?php echo $this->pageTitle;?></title>
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>
<div>
    <?php echo $content;?>
</div>

</body>
</html> 
```

Notice we had to use the `$content`. Wherever this is our views will show in page. At the top of the page we also
set the `$this->pageTitle`.

In our `DashboardController.php` we set our `layout` manually using `$this->layout`.

```php
public function actionHome(){
    $this->layout = 'basic';
    $emails = ['test@gmail.com','johndoe@gmail.com'];
    $this->render('view',['emails'=>$emails]);
}
```

Our output is shown below a combination of `basic.php` and `dashboard/view.php`.

[yii_fig_layouts_example.png]

So if you want to remove the default layout you you can change it at will or you can edit the one provided via
`main.php`.

# The Gii Tool

In `yii` we can create `controllers`, `models` and other components automatically. It does code generation for us
so we dont have to. To get it setup go to the `config/main.php` file and uncomment

```php
'gii'=>array(
    'class'=>'system.gii.GiiModule',
    'password'=>'Enter Your Password Here',
    // If removed, Gii defaults to localhost only. Edit carefully to taste.
    'ipFilters'=>array('127.0.0.1','::1'),
),
```

Change the `password` field to something you can remember. Head to `dev.yiiapp/gii` and you should be greated with a 
login page.

[yii_fig_gii_login.png]

Login and you will be shown some code generation options. Select the one that you require and begin code generating.

[yii_fig_gii_options.png]

# Models

This section requires you create or use an existing database. Go to the `config/database.php` file to setup our database.

```php
<?php

// This is the database connection configuration.
return array(
	// uncomment the following lines to use a MySQL database
	'connectionString' => 'mysql:host=localhost;dbname=wf_tutorials',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	
);
```

Add the relevant information to you in `database.php`.

We now create an `events` table.

```sql
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(255) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `event_date` varchar(255) DEFAULT NULL,
  `event_time` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

Once created we can create a model for it by heading to `dev.yiiapp.com/gii`. Select the `models` option add enter the
required information and press the **generate** button.

[yii_fig_gii_events_model.png]

Your file will be saved in `protected/models` folder with the name `Event.php`.


# Working with models

What can we do with models. Add some random data to your `event` table and lets play with models.
In the `DashboardController.php` lets create a new `action` called events.

```php
public function actionEvents()
{
    $this->render("events");
}
```

In our `/protected/views/dashboard` directory create a file called `events.php`.

## Find all

Lets get the list of all events and display it. Modify `actionEvents` to look like this

```php
public function actionEvents()
{
    $events = Event::model()->findAll();
    $this->render("events",['events'=>$events]);
}
```

We call the `static` function `model` on the `Events` class from the `model` function we call the `findAll` function.
We pass the all the data to our view using `$events`.

Now we modify the view `events.php` add the code below

```php
<?php
foreach ($events as $event) {
    echo $event->name . '<br>';
}
```

We have access to `$events` because we passed it in from the controller.

[fig_events_findall.png]

## Find By Primary Key

Lets get one event model from our database. Change the `actionEvents` to

```php
public function actionEvents()
{
    $event = Event::model()->findByPk(1);
    $this->render("events",['model'=>$event]);
}
```

`findByPk` allows us to get data via the `primary key`. Modify the `events.php` view file.

```php
<?php
echo $model->name. '<br>';
echo $model->id . '<br>';
echo $model->details . '<br>';
```

Notice how `$model` is used thats because we pass the data using the `model` key like

```php
$this->render("events",['model'=>$event]);
```

[yii_fig_events_findbypk.png]

You can do more with models. Check out some other features here

[Comment]
# More on models
Other features include `findAllByAttributes`. Where we can do something like

```php
$results = Event::model()->findAllByAttributes(['event_time'=>'10:00am']);
```

to **find all** by a certain criteria. Or we can do 

```php
$results = Event::model()->findByAttributes(['event_time'=>'10:00am']);
 ```
 
to **find one** by a certain criteria.

Learn more about models from [here](https://www.yiiframework.com/doc/api/1.1/CActiveRecord). Models
extend the class `CActiveRecord`.
[/Comment]

# Migrations

Migrations allows use to automate the tasks of creating a database. We can run away from `sql` commands and use
`php` to create your applications.

Lets create a `post` table using the `migrate` tool. To do this we need to use the `yiic` command.

```bash
php yiic migrate create posts_table
```

[yii_fig_migrate_bash.png]

A migration file will be created in the `protected/migrations` directory with some default code.

```php
<?php
      
class m191007_233526_posts_table extends CDbMigration
{
    public function up()
    {
        
    }
    
    public function down()
    {
        echo "m191007_233526_posts_table does not support migration down.\n";
        return false;
    }
}
```

The `up` allows you to create a table. The `down` is used to undo what you did in the `up` function.

Lets create the table now add this code to the `up` function.

```php
public function up()
{
    $this->createTable('posts', array(
        'id' => 'pk',
        'title' => 'string NOT NULL',
        'content' => 'text',
        'category' => 'string',
        'tags' => 'string',
        'created_at' =>'datetime'
    ));
}
```

Lets run the migration run the command

```bash
php yiic migrate
```

This creates the database you need.

[Comment]
# Errors that I encountered.

In the `config/console.php` file you need to update your `db` configuration. I encounter an error

> CDbException: CDbConnection failed to open the DB connection: SQLSTATE[HY000] [2002] No such file or directory 

So to fix this I changed the connection string from `localhost` to `127.0.0.1`.

```php
'db'=>array(
    'connectionString' => 'mysql:host=127.0.0.1;dbname=wf_tutorials',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
),
```
[/Comment]

You can do alot with `CDbMigration` learn more [here](https://www.yiiframework.com/doc/api/1.1/CDbMigration).

# Forms

Lets create a form for events. This will be a submit for where we can submit data and save it to the database.
Lets create an `EventController.php` file for our event controller. You can use gii for this if you want to
[Comment]
# Creating a controller using gii

Head to `dev.yiiapp.com/gii` in your web browser and choose the controller option.
Enter your prefix controller name. In our case its **events**. You can also choose what kind of views you want created
in the `action Ids` input section.

[yii_fig_gii_events.png]

Click generate after you choose preview and you will have an event controller.

[/Comment]

In the `EventsController.php` create a function called `actionCreate`. Create the view file in views as well


```php
public function actionCreate()
{
   $this->render('create');
}
```

The view will be `/protected/views/events/create.php`.

In `create.php` add the following code.

```php
<?php
/* @var $this EventsController */

$form =$this->beginWidget('CActiveForm', array(
    'id'=>'events-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'class'=>'form-horizontal',
    )));

echo '<label>Name</label><br>';
echo $form->textField($model, 'name',array('style'=>'')) .'<br>';

echo '<label>Details</label><br>';
echo $form->textArea($model, 'details',array('style'=>'')) . '<br>';

echo '<button type="submit" class="btn">Save</a>';

$this->endWidget();
```

To get a better understanding of whats going on we will review the sections.

[Comment]
# Form review

The fist part of maing a form is the `beginWidget`. We assign the return value to `$form`.

```php
$form =$this->beginWidget('CActiveForm', array(
'id'=>'events-form',
'enableAjaxValidation'=>false,
'htmlOptions'=>array(
    'class'=>'form-horizontal',
)));
```

The form we are creating is actually `CActiveForm`. You can learn more 
about it [here](https://www.yiiframework.com/doc/api/1.1/CActiveForm).

We added an `id` and some `htmlOptions` to our form element.

Using the `form` variable we can create some text fields.

```php
$form->textField($model, 'name',array('style'=>''))
```

or a textarea 
```php
$form->textArea($model, 'details',array('style'=>'')) 
```

we can add other options as well. We will learn more about forms in another tutorial.

[/Comment]

In our `EventsController.php` we need to pass the `$model` that we us in `create.php`. So lets do that

```php
public function actionCreate()
{
    $model = new Event();
    $this->render('create',['model'=>$model]);
}
```

So now we can see our form.

[yii_fig_events_form.png]

We are not complete as yet. We need to get the data after form submission in order to save the `event`.

## Form submission

We need to check if anything has been submitted so we use `isset`

```php
if(isset($_POST['Event'])){

}
```

Then we can assign the `$_POST` attributes to our model and save our `model`.

```php
public function actionCreate()
{
    $model = new Event();
    if(isset($_POST['Event'])){
        $model->attributes = $_POST["Event"];
        $model->save();
    }
    $this->render('create',['model'=>$model]);
}
```

Pretty simple right? 

# Access control

We can prevent users from accessing certain parts of our application by using `yii` default access control.

Lets add two functions to the `EventController.php` the `filters` and the `accessRules`

```php
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view','create'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
```

The `filters` is a function we can use to carry out actions between requests in controllers.

Lets add a new `route` called `view`.

```php
public function actionView(){
    echo "viewing a event";
}
```

As the `accessRules` are configured we can can access to this `route`. However if we remove `view`
from the `allow` array we can redirected to the login page.

```php
'actions'=>array('index','create'),
```

Learn more about `accessRules` from [here](https://www.yiiframework.com/doc/api/1.1/CAccessRule).

We can change the `users` array from astericks `*` or the email sign `@` to design who can access the views.

# Authentication

Creating a `login` from in `yii` is automatically created for you. Lets look at some of its components.
Lets look at the `SiteController.php` page for the `login` route.

```php
public function actionLogin()
{
    $model=new LoginForm;

    // if it is ajax validation request
    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
    {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }

    // collect user input data
    if(isset($_POST['LoginForm']))
    {
        $model->attributes=$_POST['LoginForm'];
        // validate user input and redirect to the previous page if valid
        if($model->validate() && $model->login())
            $this->redirect(Yii::app()->user->returnUrl);
    }
    // display the login form
    $this->render('login',array('model'=>$model));
}
```

The `LoginForm` is a model that allows use to take in the `login` form. Some things
to note in the `LoginForm` are the `authenticate` and `login`.

```php
public function authenticate($attribute,$params)
{
    if(!$this->hasErrors())
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate())
            $this->addError('password','Incorrect username or password.');
    }
}
```

```php
public function login()
{
    if($this->_identity===null)
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        $this->_identity->authenticate();
    }
    if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
    {
        $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
        Yii::app()->user->login($this->_identity,$duration);
        return true;
    }
    else
        return false;
}
```

If you look at the `login` form you can see the `UserIdentity` class. You can find this in the 
`protected/components/UserIdentity.php` file.

All this stuff is really complicated and we will have to learn more about it.

[yii_fig_login_form.png]

# Conclusion

Yii 1.1 is a really powerful MVC framework. We looked at creating routes, database operations,
views and authentication. There is alot more to learn and more topics to go in depth in. Yii also and 
a second framework that is a bit different from the first. I will do a more in depth tutorial on Yii2 as 
well. Feel free to try out Yii and all the things it can do.
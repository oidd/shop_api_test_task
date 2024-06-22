## A simple Shop API Laravel project.
A test project, still in development.

## Requirements:
- Docker

## Installation
When in root project directory:
> docker-compose up nginx --build -d

> docker-compose run php composer install

> docker-compose run artisan migrate

> docker-compose run artisan db:seed

> docker-compose up queue

## About
### Seeding
**db:seed** adds mock data to db. Also it adds a default customer (email: customer@me.com ; password: password) and default admin (email: admin@admin.com ; password: password) entries, so you don't need to. It doesn't add orders though, so you need to make them yourself. How to do this will be written below.

### Database and models
Money (columns 'balance', 'price') stored in unsigned big integer, so calculations would be as precise as possible, so we won't loose or gain small values out of nowhere when summing floats. To keep it simple to users a custom mutators and accessors were added (trait HandlesMoney). Also form requests validation won't allow user to pass a float value with more than two decimal places.

### Authentication
In this project i utilise standart TokenGuard driver, shipped with laravel itself. So tokens are stored in db in tables customers and admins. Reachable table would depend on a chosen guard (would that be a 'customer', 'admin' or it would try to reach in all at once and see if passed token is suit for any of them). In order to point desirable guard you can use 'authenticate' middleware (App\Http\Middleware\AuthenticateTokenUser) and pass a name of guard. Also there is an option 'any', and, as it was stated before, this middleware would try to reach user from all of the guards. This is an option to make some routes reachable for any registered user, but not the guests. Also auth service using abstract class as user class, so multiple models (Customer, Admin) can be reachable and stored with the same service.

### Resource services
There are a bunch of services that are being used for resource controller purposes, so i collected similar methods in trait (ResourceDefaultMethods) and added an interface (ResourceServiceContract).

### Filtering and sorting
Is implemented in App\Utils\Filtering\Filter. Filtering rules for any entity collection are defined in related service. Filter constructor requires an array of 'filtering rule' => callback, so when this filter being applied on data, it would iterate through these callbacks and apply them to Query Builder object. 
A small additional functions for form requests validation rules related to filtration are presented in trait HandlesSorting. Sorting is applied on existing attributes of the table and custom accesors if presented.

### Notifications
There are three notifications: Order created, Order status changed and report created. First one would be sent to all admins on their email. The second one would be sent to related user. Third send to admin, that requested report. I'm not using any mock mailbox, so all **sent mails will appear only in logs**. All event listeners are being queued.

### Reports in XLSX
Admin has an ability to generate reports about Orders for certain time range. Once requested, job being put into queue, and once file formed and saved, admin recieves a link for download it with unique signature.

# Usage
All endpoints starts with 'api/v1/'.
You should send 'Accept: Application/Json' header for every request. API token should be sent in Authorization: Bearer \<insert token here\> header.

**Pagination**
Every index method returns paginated data. Every index method accepts perPage attribute to specify number of elements on page (15 by default, you can change it in configs). Use page attribute to navigate.

**Sorting**
Every index method has sorting related validation rules. 
If rule like *_range presented, then you should pass two values with a '-' between them.
In order to sort by DESC, pass a '-' before attribute name.
In every index request sort might be a single value (if there is only one sorting requrement) or a proper query array.

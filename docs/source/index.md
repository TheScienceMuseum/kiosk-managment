---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/./docs//collection.json)

<!-- END_INFO -->

#Kiosk
<!-- START_f44b51482b6b73312c043481f4af9c3e -->
## api/kiosk

> Example request:

```bash
curl -X GET -G "http://kiosk-manager.test/api/kiosk" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "data": [
        {
            "name": "omnis culpa vitae",
            "address": "0b04e170-b763-368c-b5d0-4536a6ced73a",
            "path": "http:\/\/localhost\/api\/kiosk\/1"
        },
        {
            "name": "voluptates id error",
            "address": "d44aaa39-6f4f-360b-9852-42930ceb1f4e",
            "path": "http:\/\/localhost\/api\/kiosk\/2"
        },
        {
            "name": "aut sit voluptatem",
            "address": "009eac63-1f4f-3a8e-a2d0-5f8c0b1421ae",
            "path": "http:\/\/localhost\/api\/kiosk\/3"
        },
        {
            "name": "facere consequatur voluptas",
            "address": "0b1112c5-4465-3f94-be02-d6c8e597457c",
            "path": "http:\/\/localhost\/api\/kiosk\/4"
        },
        {
            "name": "et non iure",
            "address": "f09aeb6d-4049-35a2-9bbf-b104f1d94637",
            "path": "http:\/\/localhost\/api\/kiosk\/5"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/kiosk?page%5Bnumber%5D=1",
        "last": "http:\/\/localhost\/api\/kiosk?page%5Bnumber%5D=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http:\/\/localhost\/api\/kiosk",
        "per_page": 10,
        "to": 5,
        "total": 5
    }
}
```

### HTTP Request
`GET api/kiosk`


<!-- END_f44b51482b6b73312c043481f4af9c3e -->

<!-- START_5e452917b28cb828fe01b098216b3190 -->
## api/kiosk

> Example request:

```bash
curl -X POST "http://kiosk-manager.test/api/kiosk" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/kiosk`


<!-- END_5e452917b28cb828fe01b098216b3190 -->

<!-- START_496db657acb9e7d94a57bc3acec91dee -->
## api/kiosk/{kiosk}

> Example request:

```bash
curl -X GET -G "http://kiosk-manager.test/api/kiosk/{kiosk}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{kiosk}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "message": "This action is unauthorized.",
    "exception": "Symfony\\Component\\HttpKernel\\Exception\\AccessDeniedHttpException",
    "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Exceptions\/Handler.php",
    "line": 202,
    "trace": [
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Exceptions\/Handler.php",
            "line": 176,
            "function": "prepareException",
            "class": "Illuminate\\Foundation\\Exceptions\\Handler",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/app\/Exceptions\/Handler.php",
            "line": 49,
            "function": "render",
            "class": "Illuminate\\Foundation\\Exceptions\\Handler",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/nunomaduro\/collision\/src\/Adapters\/Laravel\/ExceptionHandler.php",
            "line": 68,
            "function": "render",
            "class": "App\\Exceptions\\Handler",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 83,
            "function": "render",
            "class": "NunoMaduro\\Collision\\Adapters\\Laravel\\ExceptionHandler",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 32,
            "function": "handleException",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 681,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 656,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 622,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 611,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 176,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 151,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 116,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/LaravelGenerator.php",
            "line": 79,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/AbstractGenerator.php",
            "line": 222,
            "function": "callRoute",
            "class": "Mpociot\\ApiDoc\\Generators\\LaravelGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/AbstractGenerator.php",
            "line": 88,
            "function": "getRouteResponse",
            "class": "Mpociot\\ApiDoc\\Generators\\AbstractGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php",
            "line": 292,
            "function": "processRoute",
            "class": "Mpociot\\ApiDoc\\Generators\\AbstractGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php",
            "line": 95,
            "function": "processRoutes",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "function": "handle",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 571,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 183,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Command\/Command.php",
            "line": 255,
            "function": "execute",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 170,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Command\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 886,
            "function": "run",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 262,
            "function": "doRunCommand",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 145,
            "function": "doRun",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Application.php",
            "line": 89,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Console\/Kernel.php",
            "line": 122,
            "function": "run",
            "class": "Illuminate\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/artisan",
            "line": 37,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Console\\Kernel",
            "type": "->"
        }
    ]
}
```

### HTTP Request
`GET api/kiosk/{kiosk}`


<!-- END_496db657acb9e7d94a57bc3acec91dee -->

<!-- START_018d25a2993c568828e6d3f5b434b7ee -->
## api/kiosk/{kiosk}

> Example request:

```bash
curl -X PUT "http://kiosk-manager.test/api/kiosk/{kiosk}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{kiosk}",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/kiosk/{kiosk}`


<!-- END_018d25a2993c568828e6d3f5b434b7ee -->

<!-- START_e6d8af718bb611734d5349da3d923ea1 -->
## api/kiosk/{kiosk}

> Example request:

```bash
curl -X DELETE "http://kiosk-manager.test/api/kiosk/{kiosk}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{kiosk}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/kiosk/{kiosk}`


<!-- END_e6d8af718bb611734d5349da3d923ea1 -->

<!-- START_8128e4e98b8b21368d219d1e7acbcd56 -->
## api/kiosk/{address}/health-check

> Example request:

```bash
curl -X POST "http://kiosk-manager.test/api/kiosk/{address}/health-check" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{address}/health-check",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/kiosk/{address}/health-check`


<!-- END_8128e4e98b8b21368d219d1e7acbcd56 -->

<!-- START_45e3510040260130e433dddf8828d106 -->
## api/kiosk/{address}/package-update

> Example request:

```bash
curl -X POST "http://kiosk-manager.test/api/kiosk/{address}/package-update" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{address}/package-update",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/kiosk/{address}/package-update`


<!-- END_45e3510040260130e433dddf8828d106 -->

<!-- START_e49b5fa303a12aec9caca5e507c7841d -->
## api/kiosk/{address}/register

> Example request:

```bash
curl -X POST "http://kiosk-manager.test/api/kiosk/{address}/register" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/kiosk/{address}/register",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/kiosk/{address}/register`


<!-- END_e49b5fa303a12aec9caca5e507c7841d -->

#User
<!-- START_2b6e5a4b188cb183c7e59558cce36cb6 -->
## api/user

> Example request:

```bash
curl -X GET -G "http://kiosk-manager.test/api/user" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/user",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "data": [
        {
            "name": "Lawrence",
            "email": "lawrence@joipolloi.com",
            "roles": [
                {
                    "name": "developer"
                },
                {
                    "name": "administrator"
                },
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/1"
        },
        {
            "name": "Americo Krajcik",
            "email": "arlene70@example.com",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/2"
        },
        {
            "name": "Prof. Haylee Luettgen",
            "email": "joshuah.russel@example.net",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/3"
        },
        {
            "name": "Dolores Kiehn",
            "email": "ruth77@example.net",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/4"
        },
        {
            "name": "Mr. Randall Gleichner I",
            "email": "bernhard.chauncey@example.org",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/5"
        },
        {
            "name": "Harvey Herman V",
            "email": "balistreri.brannon@example.net",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/6"
        },
        {
            "name": "Ernestina Hartmann",
            "email": "patience.cronin@example.net",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/7"
        },
        {
            "name": "Ms. Lorna Kuvalis",
            "email": "alessia77@example.com",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/8"
        },
        {
            "name": "Bret Balistreri Sr.",
            "email": "ross.lang@example.org",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/9"
        },
        {
            "name": "Hope Wuckert",
            "email": "angeline63@example.com",
            "roles": [
                {
                    "name": "content writer"
                },
                {
                    "name": "kiosk admin"
                }
            ],
            "path": "http:\/\/localhost\/api\/user\/10"
        }
    ],
    "links": {
        "first": "http:\/\/localhost\/api\/user?page%5Bnumber%5D=1",
        "last": "http:\/\/localhost\/api\/user?page%5Bnumber%5D=3",
        "prev": null,
        "next": "http:\/\/localhost\/api\/user?page%5Bnumber%5D=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "path": "http:\/\/localhost\/api\/user",
        "per_page": 10,
        "to": 10,
        "total": 23
    }
}
```

### HTTP Request
`GET api/user`


<!-- END_2b6e5a4b188cb183c7e59558cce36cb6 -->

<!-- START_f0654d3f2fc63c11f5723f233cc53c83 -->
## api/user

> Example request:

```bash
curl -X POST "http://kiosk-manager.test/api/user" \
    -H "Accept: application/json" \
    -d "name"="unde" \
        -d "email"="felipa52@example.org" \
        -d "send_invite"="1" \
        -d "roles"="unde" \
        -d "roles.0"="unde" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/user",
    "method": "POST",
    "data": {
        "name": "unde",
        "email": "felipa52@example.org",
        "send_invite": true,
        "roles": "unde",
        "roles.0": "unde"
    },
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/user`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | 
    email | email |  required  | 
    send_invite | boolean |  required  | 
    roles | array |  optional  | Must be an array
    roles.0 | string |  optional  | Valid role name

<!-- END_f0654d3f2fc63c11f5723f233cc53c83 -->

<!-- START_ceec0e0b1d13d731ad96603d26bccc2f -->
## api/user/{user}

> Example request:

```bash
curl -X GET -G "http://kiosk-manager.test/api/user/{user}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/user/{user}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "message": "Trying to get property 'id' of non-object",
    "exception": "ErrorException",
    "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/app\/Http\/Requests\/UserShowRequest.php",
    "line": 18,
    "trace": [
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/app\/Http\/Requests\/UserShowRequest.php",
            "line": 18,
            "function": "handleError",
            "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
            "type": "->"
        },
        {
            "function": "authorize",
            "class": "App\\Http\\Requests\\UserShowRequest",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 571,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/FormRequest.php",
            "line": 150,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Validation\/ValidatesWhenResolvedTrait.php",
            "line": 19,
            "function": "passesAuthorization",
            "class": "Illuminate\\Foundation\\Http\\FormRequest",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Providers\/FormRequestServiceProvider.php",
            "line": 30,
            "function": "validateResolved",
            "class": "Illuminate\\Foundation\\Http\\FormRequest",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 1064,
            "function": "Illuminate\\Foundation\\Providers\\{closure}",
            "class": "Illuminate\\Foundation\\Providers\\FormRequestServiceProvider",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 1028,
            "function": "fireCallbackArray",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 1013,
            "function": "fireAfterResolvingCallbacks",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 672,
            "function": "fireResolvingCallbacks",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 608,
            "function": "resolve",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Application.php",
            "line": 733,
            "function": "make",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/RouteDependencyResolverTrait.php",
            "line": 79,
            "function": "make",
            "class": "Illuminate\\Foundation\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/RouteDependencyResolverTrait.php",
            "line": 46,
            "function": "transformDependency",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/RouteDependencyResolverTrait.php",
            "line": 27,
            "function": "resolveMethodDependencies",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/ControllerDispatcher.php",
            "line": 41,
            "function": "resolveClassMethodDependencies",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php",
            "line": 212,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php",
            "line": 169,
            "function": "runController",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 679,
            "function": "run",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 681,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 656,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 622,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 611,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 176,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 151,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 116,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/LaravelGenerator.php",
            "line": 79,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/AbstractGenerator.php",
            "line": 222,
            "function": "callRoute",
            "class": "Mpociot\\ApiDoc\\Generators\\LaravelGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/AbstractGenerator.php",
            "line": 88,
            "function": "getRouteResponse",
            "class": "Mpociot\\ApiDoc\\Generators\\AbstractGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php",
            "line": 292,
            "function": "processRoute",
            "class": "Mpociot\\ApiDoc\\Generators\\AbstractGenerator",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php",
            "line": 95,
            "function": "processRoutes",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "function": "handle",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 571,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 183,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Command\/Command.php",
            "line": 255,
            "function": "execute",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 170,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Command\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 886,
            "function": "run",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 262,
            "function": "doRunCommand",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/symfony\/console\/Application.php",
            "line": 145,
            "function": "doRun",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Application.php",
            "line": 89,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Console\/Kernel.php",
            "line": 122,
            "function": "run",
            "class": "Illuminate\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/home\/vagrant\/Code\/Clients\/ScienceMuseum\/Kiosk-Manager\/artisan",
            "line": 37,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Console\\Kernel",
            "type": "->"
        }
    ]
}
```

### HTTP Request
`GET api/user/{user}`


<!-- END_ceec0e0b1d13d731ad96603d26bccc2f -->

<!-- START_1857d3df71d357b05fb022b3b344cf91 -->
## api/user/{user}

> Example request:

```bash
curl -X PUT "http://kiosk-manager.test/api/user/{user}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/user/{user}",
    "method": "PUT",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/user/{user}`


<!-- END_1857d3df71d357b05fb022b3b344cf91 -->

<!-- START_4bb7fb4a7501d3cb1ed21acfc3b205a9 -->
## api/user/{user}

> Example request:

```bash
curl -X DELETE "http://kiosk-manager.test/api/user/{user}" \
    -H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://kiosk-manager.test/api/user/{user}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/user/{user}`


<!-- END_4bb7fb4a7501d3cb1ed21acfc3b205a9 -->



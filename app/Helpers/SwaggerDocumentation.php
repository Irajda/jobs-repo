<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class SwaggerDocumentation
{

    function getRouteNames(){
        $data=[];
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $value) {
            $action = explode('@',$value->getActionName());
            if( strpos($action[0],"Http\\Controllers\\Api\\") == 0) continue;
            $data[] = [
                "method" => $value->methods()[0],
                "path" => $value->uri,
                "controller" => $action[0],
                "function" => $action[1] ?? '__invoke',
                "action" => $value->getActionName(),
                "middleware" => $value->gatherMiddleware(),
            ];
        }
        return $data;
    }



    function generateApis(){
        $routes = $this->getRouteNames();
        $apis=[];
        foreach($routes as $route){
            $isPublic = in_array("auth:sanctum",$route['middleware']);
            $swgParams=[];
            $controller = new \ReflectionClass($route["controller"]);
            $cParams = $controller->getMethod($route['function'])->getParameters();

            $arr = explode("{",$route['path']);
            foreach($arr as $str){
                $val = explode("}",$str);
                if(count($val) > 1){
                    $swgParams[]=[
                        "name"=>$val[0],
                        "name"=>$val[0],
                        "in"=>"path",
                        "required"=>"true",
                        "schema"=>[
                            "type"=>"integer"
                        ]
                    ];
                }
            }

            foreach($cParams as $cParam){
                if($cParam->name == "request"){
                    $requestNS = $cParam->getType()->getName();
                    if( $requestNS == "Illuminate\Http\Request") continue;
                    $params = (new $requestNS)->rules();
                    foreach($params as $name => $rules){
                        $rules = ! is_array($rules) ? explode("|",$rules) : $rules;

                        $type = in_array("integer",$rules)?"integer":"string";
                        in_array("boolean",$rules) && $type="boolean";
                        in_array("numeric",$rules) && $type="integer";
                        in_array("email",$rules) && $type="email";
                        in_array("accepted",$rules) && $type="boolean";

                        $swgParam=[
                            "name"=>$name,
                            "name"=>$name,
                            "in"=>"query",
                            "schema"=>[
                                "type"=>$type
                            ]
                        ];
                        if( in_array("required",$rules) ){
                            $swgParam["required"]="true";
                        }
                        $swgParams[]=$swgParam;
                    }
                    break;
                }
            }

            if(!isset($apis[ "/".$route['path'] ])){
                $apis[ "/".$route['path'] ] = [];
            }

            $apis[ "/".$route['path'] ][strtolower($route['method'])]=[
                "tags" => [ !$isPublic?"public":"auth"],
                "summary"=> $route['action'],
                "description"=>$route['function'],
                "operationId"=>$route['action'],
                "parameters"=>$swgParams,
                "responses"=>[
                    "200"=>[
                        "description" => "Successful"
                    ]
                ],
                "security" => [
                    $isPublic?[]:["bearer"=>[]]
                ]
            ];
        }
        return $apis;
    }


    function makeJson(){
        $apis = $this->generateApis();

        $url  = env("APP_URL");
        $domains = [
            [
                "url"=>$url."/".env("APP_PATH"),
               // "description"=>"",
            ]
        ];

        $data = [
            "openapi"=>"3.0.0",
            "info"=> [
                "title"=> "JOB LIST",
                "version"=> "1.0.0"
            ],
            "servers"=> $domains,
            "paths"=>$apis,
            "components"=> [
                "securitySchemes"=>[
                    "bearer"=>[
                        "type"=> "apiKey",
                        "description"=> "Enter token in format (Bearer <token>)",
                        "name"=> "Authorization",
                        "in"=> "header"
                    ]
                ]
            ],
            "security"=> [
                []
            ]
        ];

        File::put( config('l5-swagger.defaults.paths.docs')."/api-docs.json", json_encode($data,JSON_PRETTY_PRINT) );

    }
}

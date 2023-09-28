## 本地搭建 
* 官网地址 http://laravel.p2hp.com/cndocs/10.x/installation
* 终端执行 命令
```
curl -s "https://laravel.build/example-app" | bash
```
## 启动运行环境
```
./vendor/bin/sail up -d  
```
## 生成接口文档
```
./vendor/bin/sail   artisan l5-swagger:generate
```
## 访问接口文档
* 本地host http://本地host/api/documentation#/
![](https://github.com/secretgao/test_laravel8/blob/main/1695864399281.jpg)

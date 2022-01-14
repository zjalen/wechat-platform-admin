# 微信平台管理——前端 (wechat-platform-admin/frontend)

微信平台管理——支持微信公众平台、开放平台可视化管理。

## 快速开始

以下指令均使用 `yarn` 进行，可自行根据情况换成 `npm` 和 `npx`

### 安装依赖
```bash
yarn
```

### 配置环境文件

- linux 和 mac 下，可以直接使用 `yarn env`
- windows 下请复制 `.env.example` 为 `.env` 文件

### 启动开发模式

请先根据后台部署的地址修改 `.env` 中的 `API_URL_DEV` 项，为实际后台地址，例如当你使用，`php artisan serve --port=8000` 启动后台时，可设置为：`API_URL_DEV=http://localhost:8000`。前端框架已配置了代理以解决跨域问题：
```js
// quasar.conf.js
module.exports = configure(function (ctx) {
  return {
    // ...省略其它代码

    // Full list of options: https://quasar.dev/quasar-cli/quasar-conf-js#Property%3A-devServer
    devServer: {
      proxy: {
        // proxy all requests starting with /api to jsonplaceholder
        "/api": {
          target: dotEnv.API_URL_DEV,
          changeOrigin: true,
          pathRewrite: {
            "^/api": "api",
          },
        },
      },
    },
  }
```

启动前端服务，根据提示访问开发地址即可，默认 `localhost:8080` ：

```bash
yarn dev
```

### 打包

#### 前后端分离
1. 后台 API 设置

先修改 `.env` 中的后台 api 接口配置项 `API_URL=` 为您需要的后台地址。留空则默认使用与前端一样的地址，您可使用如 `nginx` 配置一个反向代理以解决跨域问题。

```bash
# nginx server 反向代理
location ~ ^/api
{
    rewrite  ^/api/?(.*)$ /api/$1 break;
    # 此处修改为你的后台 api 地址
    proxy_pass http://localhost:8000;
}
```

假如 `API_URL=` 设置后台 API 地址与前端不一致，则需要您在后台手动开启允许跨域的相关选项，如 `nginx`：

```bash
location / {
    # '*' 表示允许所有地址跨域，您可改为指定域名或 ip 地址
    add_header Access-Control-Allow-Origin *;
    add_header Access-Control-Allow-Credentials true;
    add_header Access-Control-Allow-Methods 'GET, POST, OPTIONS';
    add_header Content-Security-Policy "upgrade-insecure-requests";
    if (!-e $request_filename) {
          rewrite ^/(.*)$ /index.php/$1 last;
    }
}
```

2. 执行打包
```bash
quasar build
```

打包后文件在 `dist/spa` 目录下，正式部署时可将此文件夹内容部署在服务器上使用

#### 使用 laravel 模板渲染

本项目也支持直接使用 `laravel` 的渲染模板来将前后端代码统一部署在一起，这样就没有跨域问题。

1. 先修改 `.env` 中的后台 api 接口配置项 `API_URL=` 直接留空。
2. 使用指定打包指令：

> ！！！注意：此命令仅适合 mac 或 linux 下使用，它先删除已有旧版本资源，打包后复制资源到原来位置。windows
 用户可手动进行相关操作。

```bash
yarn build-for-laravel
```

此命令包含以下几步：
- 删除**项目根目录(并非前端根目录)下** `public/frontend/` 中的静态资源，包括 `js`、`css` 等
- 删除**项目根目录(并非前端根目录)下** `resources/views/frontend.blade.php` 文件，即该页面前端模板，对应前端的 `index.html`
- 指定环境参数进行打包 `FOR_LARAVEL=true node_modules/.bin/quasar build`
- 拷贝打包后的文件到**项目根目录(并非前端根目录)下** `public/frontend/`
- 拷贝入口文件 `index.html` 为后端 `laravel` 模板 `resources/views/frontend.blade.php`

完成以上步骤后，直接访问后端入口，默认路由即渲染出前端项目。

## 代码结构

```text
.
├── README.md
├── babel.config.js
├── dist  # 打包后输出文件
│   └── spa  # 打包后的 spa 资源
├── jsconfig.json
├── package.json
├── public
│   ├── favicon.ico
│   └── icons
├── quasar.conf.js  # 前端核心配置文件，参见 quasar 文档
├── src  # 前端核心代码
│   ├── App.vue
│   ├── api  # 接口定义
│   ├── assets  # 资源文件
│   ├── boot  # 插件启动配置项
│   ├── components  # 组件库
│   ├── css
│   ├── index.template.html  # index 页面模板
│   ├── layouts  # 页面结构
│   ├── pages  # 页面内容
│   ├── quasar.d.ts
│   ├── router  # 路由
│   ├── store  # vuex 状态管理
│   └── utils  # 自定义工具
├── .eslintrc.js  # eslint 相关配置
└── yarn.lock
```

## 其它

### 代码严格检查

项目默认使用 `eslint` 和 `prettier` 进行代码严格检查，相关配置在 `.eslintrc.js` 中，您可使用 `eslint` 相关命令进行代码严格检查和修复。您也可以参考 `eslint` 和 `prettier` 官网配置在 IDE 中代码规范检查的异常显示和保存时自动修复。

```js
// 代码严格检查
yarn lint
// 检查代码格式并进行修复，注意代码会按照 prettier 默认配置进行格式化
yarn lint --fix
```

### 更多的 quasar 自定义配置项
请参考 [Configuring quasar.conf.js](https://quasar.dev/quasar-cli/quasar-conf-js).

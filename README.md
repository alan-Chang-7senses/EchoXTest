# 30點競速遊戲 API

提供給 30 點競速遊戲前端 Unity 使用的後端邏輯與資料存取相關 API。

## 資料夾

### deploy - 環境部署檔案

 - __Dockerfile：__ 用來 build 執行環境檔案，內容同時包含版號以及 development 與 production 環境敘述。
 - __docker-compose.yml：__ 用於 build image 以及開發期部署環境用的 YAML 檔案，通常用於開發期，正式環境部署需要視需求更動。
 - __docker-php-ext-opcache.ini：__ PHP 啟用 OPcache 所使用的設定檔案。
 - __docker-php-ext-xdebug.ini：__ 開發時期除錯，啟用 XDebug 的設定檔案。
 - __koa\_main.sql：__ 遊戲主資料庫的 SQL 語法。
 - __koa\_static.sql：__ 遊戲靜態資料庫的 SQL 語法。
 - __launch.json：__ Visual Studio Code 的 PHP Debug 所使用的設定檔案，主要於開發期啟用 XDebug 進行斷點時使用。
 - __php.ini-development：__ 開發環境用的 php.ini。
 - __php.ini-production：__ 發行環境用的 php.ini。

### docs - API 說明文件資料夾

- [文件首頁](docs/api/index.md)。

### src - 程式原始碼檔案與資料夾

當有詢問請求進來時，將經由 .htaccess 敘述的 rewrite 導向 index.php 進行處理，或依據 .htaccess 敘述的例外直接執行。

- __logs：__ 系統運行的 log 存放區，通常為 error log。

## 環境部署

### 步驟

1. 從 **deploy** 資料夾複製檔案到上一層：
	- Dockerfile。
	- docker-compose.yml。
	- docker-php-ext-opcache.ini。
	- php.ini-development。
	- php.ini-production。
2. 修改 **Dockerfile** 檔案中的 APP_VERSION 環境變數定義版號。
3. 修改 docker-compose.yml 檔案內容：
	- 修改 ports。
	- 修改環境變數。
	- 改變 target 的註解來指定環境（development 或 production）。
4. 輸入指令構建 docker image：

		docker-compose build
		
5. 輸入指令開起 Container：

		docker-compose up -d

### 環境變數

- SYSENV：指定系統環境名稱，通常用於開發時期，可針對開發期需求判斷此變數值建立特定功能。
	- 發行環境不需提供此環境變數。
	- developement：代表系統於開發環境。
- DB_LABEL：欲連接的預設資料庫標記名稱。
- DB_HOST：資料庫主機位址。
- DB_PORT：資料庫主機 Port。
- DB_USERNAME：資料庫使用者名稱。
- DB_PASSWORD：資料庫使用者密碼。
- DB_NAME：資料庫名稱。
- MEMCACHED_HOST：Memcache Server 位址。
- MEMCACHED_PORT：Memcache Server Port。

## XDebug for Visual Studio Code

在 Container 內安裝 XDebug，使外部的 VS Code 編輯器可透過下中斷點偵查程式運行獲取資訊。

### 執行環境

1. docker-compose.yml 的 target 切換到 development。
2. 輸入指令構建 docker image：

		docker-compose build

3. docker-compose.yml 添加環境變數：
	- XDEBUG\_CLIENT\_HOST：監聽 XDebug 的 IDE 所在 IP。
	- XDEBUG\_CLIENT\_PORT：監聽 XDebug 的 IDE 指定 Port。
4. 輸入指令釋放並重新開起 Container：

		docker-compose down; docker-compose up -d

### Visual Studio Code

1. 安裝插件 PHP Debug。
2. 使用 Visual Studio Code 開啟專案資料夾。
3. 複製 deploy/launch.json 到 .vscode/launch.json：
	-  .vscode 資料夾為隱藏資料夾。
	-  可先透過執行 Debug 來產生檔案再行覆蓋。
4. 修改 .vscode/launch.json 內容：
	-  hostname：編輯器所在 IP。  
（需與 XDEBUG\_CLIENT\_HOST 相同）
	-  port：監聽的 port。  
（需與 XDEBUG\_CLIENT\ _PORT 相同）

### Postman

1. 點擊右上方 Send 按鈕下方的「Cookies」來開啟 MANAGE COOLIES 視窗。
2. 在 Type a domain name 欄位輸入「XDEBUG_SESSION=VSCODE」，並點擊 Add 按鈕後關閉視窗。
3. 在 Headers 添加 KEY 為「Cookie」，VALUE 為「XDEBUG_SESSION=VSCODE」。
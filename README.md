# Peta Rush 遊戲後端 API

 - 提供給 Peta Rush 遊戲前端 Unity 使用的後端邏輯與資料存取相關 API。
 - 包含輔助工作使用的工具 API。
 - 包含使用命令列執行的 CLI 工具程式。

## 資料夾

### deploy - 環境部署檔案

 - __Dockerfile：__ 用來 build 執行環境檔案，內容同時包含版號以及 development 與 production 環境敘述。
 - __docker-compose.yml：__ 用於 build image 以及開發期部署環境用的 YAML 檔案，通常用於開發期，正式環境部署需要視需求更動。
 - __docker-php-ext-opcache.ini：__ PHP 啟用 OPcache 所使用的設定檔案。
 - __docker-php-ext-xdebug.ini：__ 開發時期除錯，啟用 XDebug 的設定檔案。
 - __koa\_elitetest.sql：__ 菁英測試資料庫的 SQL 語法。
 - __koa\_log.sql：__ 遊戲 Log 資料庫的 SQL 語法。
 - __koa\_main.sql：__ 遊戲主資料庫的 SQL 語法。
 - __koa\_static.sql：__ 遊戲靜態資料庫的 SQL 語法。
 - __launch.json：__ Visual Studio Code 的 PHP Debug 所使用的設定檔案，主要於開發期啟用 XDebug 進行斷點時使用。
 - __php.ini-development：__ 開發環境用的 php.ini。
 - __php.ini-production：__ 發行環境用的 php.ini。

### docs - API 說明文件資料夾

- [文件首頁](docs/api/index.md)。

### src - 程式原始碼檔案與資料夾

當有詢問請求進來時，將經由 .htaccess 敘述的 rewrite 導向 index.php 進行處理，或依據 .htaccess 敘述的例外直接執行。

- __logs：__ 系統運行的 log 存放區，通常為 error log，環境變數 SYSENV 為 local 時才有作用。
- __cli：__ 透過命令列介面（CLI）執行的程式。

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
	- local：本機環境，此環境才會紀錄 log 檔案。
	- developement：系統處於開發環境。
- PROCESS_TIMING：是否回傳處理計時，無此環境變數或變數值不為「字串 true」，代表不回傳。
- MAINTAIN：是否維護中，無此環境變數或變數值不為「字串 true」，代表非維護中。
- DB\_MAIN\_HOST：「主遊戲」資料庫主機位址。
- DB\_MAIN\_PORT：「主遊戲」資料庫主機 Port。
- DB\_MAIN\_USERNAME：「主遊戲」資料庫使用者名稱。
- DB\_MAIN\_PASSWORD：「主遊戲」資料庫使用者密碼。
- DB\_MAIN\_NAME：「主遊戲」資料庫名稱。
- DB\_STATIC\_HOST：「遊戲設定數據」資料庫主機位址。
- DB\_STATIC\_PORT：「遊戲設定數據」資料庫主機 Port。
- DB\_STATIC\_USERNAME：「遊戲設定數據」資料庫使用者名稱。
- DB\_STATIC\_PASSWORD：「遊戲設定數據」資料庫使用者密碼。
- DB\_STATIC\_NAME：「遊戲設定數據」資料庫名稱。
- DB\_LOG\_HOST：「遊戲 Log」資料庫主機位址。
- DB\_LOG\_PORT：「遊戲 Log」資料庫主機 Port。
- DB\_LOG\_USERNAME：「遊戲 Log」資料庫使用者名稱。
- DB\_LOG\_PASSWORD：「遊戲 Log」資料庫使用者密碼。
- DB\_LOG\_NAME：「遊戲 Log」資料庫名稱。
- DB\_ELITE\_HOST：「菁英測試」資料庫主機位址。
- DB\_ELITE\_PORT：「菁英測試」資料庫主機 Port。
- DB\_ELITE\_USERNAME：「菁英測試」資料庫使用者名稱。
- DB\_ELITE\_PASSWORD：「菁英測試」資料庫使用者密碼。
- DB\_ELITE\_NAME：「菁英測試」資料庫名稱。
- MEMCACHED\_HOST：Memcache Server 位址。
- MEMCACHED\_PORT：Memcache Server Port。
- SSO\_URI：SSO 登入的環境網址。
- SSO\_CLIEND\_ID：SSO 登入的環境 Client ID。
- SSO\_CLIEND\_SECRET：SSO 登入的註冊安全碼。
- APP\_URI：API 本身的環境網址。
- NFT\_URI：NFT 串接環境網址。
- NFT\_CLIENTID：NFT 串接環境的 Client ID。
- NFT\_APISECRET：NFT 串接環境的 API Secret。
- QuickSDK\_CallBackKey\_Android：QuickSDK儲值回調的Android平台簽證密鑰
- QuickSDK\_CallBackKey\_iOS：QuickSDK儲值回調的iOS平台簽證密鑰
- MYCARD\_SWITCH：MyCard 購買開關
- MYCARD\_URI：MyCard 溝通的API   
- MYCARD\_ALLOWIP：允許 MyCard 平台進入查詢的IP
- MYCARD\_FACSERVICEID： MyCard 遊戲服務ID
- MYCARD\_FACGAMEID： 遊戲名稱(英文)
- MYCARD\_FACGAMENAME： 遊戲名稱
- MYCARD\_SANDBOXMODE：沙盒測試開關
- MYCARD\_FACKEY：MyCard 簽證密鑰
- GOOGLE\_APPLICATION\_CREDENTIALS: 內建Google憑證位置(變數名稱不能改)
- GOOGLE\_APIKEY: Google APIKEY 密鑰
- GOOGLE\_PACKAGENAME: 前端目前使用的包名

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
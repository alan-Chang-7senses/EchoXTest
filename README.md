# Peta Rush 遊戲後端 API

 - 提供給 Peta Rush 遊戲前端 Unity 使用的後端遊戲邏輯與資料存取相關 API。
 - 包含輔助工作使用的工具 API。
 - 包含使用命令列執行的 CLI 工具程式。
 - 包含 Metasens 相關串接 API（SSO 登入、NFT 串接、點數系統）。
 - 包含 MyCard 金流串接 API。

## 資料夾

### deploy - 環境部署檔案

 - __Dockerfile：__ 用來 build 執行環境檔案，內容同時包含版號以及 development 與 production 環境敘述。
 - __docker-compose.yml：__ 用於 build image 以及開發期部署環境用的 YAML 檔案，通常用於開發期，正式環境部署需要視需求更動。
 - __docker-php-ext-opcache.ini：__ PHP 啟用 OPcache 所使用的設定檔案。
 - __docker-php-ext-xdebug.ini：__ 開發時期除錯，啟用 XDebug 的設定檔案。
 - __launch.json：__ Visual Studio Code 的 PHP Debug 所使用的設定檔案，主要於開發期啟用 XDebug 進行斷點時使用。
 - __php.ini-development：__ 開發環境用的 php.ini。
 - __php.ini-production：__ 發行環境用的 php.ini。

### docs - 說明文件資料夾

- [API 文件](docs/api/index.md)。
- [資料庫備註說明](docs/db/index.md)。

### sql - 部署與更新版本 SQL 檔案資料夾

 - __koa\_elitetest.sql：__ 菁英測試資料庫的 SQL 語法。
 - __koa\_elitetest\_users.sql：__ 菁英測試對外開放給玩家使用帳號的 SQL 語法。
 - __koa\_elitetest\_users\_test.sql：__ 菁英測試內部測試帳號的 SQL 語法。
 - __koa\_log.sql：__ 遊戲 Log 資料庫的 SQL 語法。
 - __koa\_main.sql：__ 遊戲主資料庫的 SQL 語法。
 - __koa\_static.sql：__ 遊戲靜態資料庫的 SQL 語法。
- __其餘「v版號」開頭的 .sql 檔案：__ 更新版本時，相關異動的 SQL 語法；屬於暫存檔案，已整合至前述 SQL 檔案中，未來可刪除。

### src - 程式原始碼檔案與資料夾

當有詢問請求進來時，將經由 .htaccess 敘述的 rewrite 導向 index.php 進行處理，或依據 .htaccess 敘述的例外直接執行。


- __classes：__ 程式主要內容皆在此資料夾中。
	- __Accessors：__ 系統基本存取功能相關程式。
	- __Consts：__ 系統基本常數定義。
	- __Exceptions：__ 系統基本例外類，提供遊戲前端的例外皆需繼承此處的 NormalException，並對應至 exceptions 資料夾中的訊息 。
	- __Games：__ 遊戲主要內容程式。
		- __CommandWorkers：__ 命令列工作器，用於命令列介面（CLI）執行的命令功能。
		- __Exceptions：__ 提供給遊戲前端的例外定義，皆須繼承 NormalException 並於 exceptions 資料夾宣告定應訊息。
	- __Processors：__ API 處理器，API 路徑將導向此處執行對應路徑中的程式。
- __cli：__ 透過命令列介面（CLI）執行的程式。
- __exceptions：__ 回應給遊戲前端的例外訊息。
- __logs：__ 系統運行的 log 存放區，通常為 error log，環境變數 SYSENV 為 local 時才有作用。
- __.htaccess：__ Apache 伺服器系統目錄權限規則設置檔案，
- __index.php：__ API 入口，在此運行指定路徑 API 並處理錯誤及成功回應訊息。

## 開發環境建置

### 安裝軟體

- Docker（必要）：至 [Docker 網站](https://www.docker.com/) 依據本地作業系統下載並安裝適合的 Docker Desktop。
- Postman：用於測試 API。
- HeidiSQL：用於管理資料庫。

### 部署步驟

1. 從 **deploy** 資料夾將以下檔案複製到上一層目錄（即專案目錄下）：
	- Dockerfile。
	- docker-compose.yml。
	- docker-php-ext-opcache.ini。
	- php.ini-development。
2. 修改 **Dockerfile** 檔案中的 APP_VERSION 環境變數定義版號。
3. 修改 **docker-compose.yml** 檔案內容，將 target: development 取消註解。
4. 使用命令模式（PC 的 cmd 或 PowerShell，Mac 的終端機 - Terminal）前往專案目錄，輸入指令構建 docker image：

		docker-compose build
		
5. 輸入指令開起 Container：

		docker-compose up -d
		
6. 使用 HeidiSQL 或 phpMyAdmin 等工具連接資料庫：
	- IP：127.0.0.1 或裝置本地 IP。
	- Port：37002。
	- Username：root。
	- Password：1111。
7. 取得 **sql** 資料夾將以下檔案內容匯入到資料庫：
	- koa_log.sql。
	- koa_main.sql。
	- koa_static.sql。
- 使用 Postman 或任意瀏覽器調用登入 API（不需任何參數），若回應以下資訊，即表示環境部署完成：  
<http://localhost:37001/User/Login>

		{
		    "error": {
		        "code": 26,
		        "message": "No parameter 'account'"
		    }
		}


## 正式環境部署

### 要項說明

- 此 API 架構主要分為 Web API、Database（資料庫）、Memcached（快取）三個部分，Database 與 Memcached 依據實際環境另外部署，僅需構建 Web API 並在部署時於環境變數設置 Database 與 Memcached 相關資訊。
- Web API 將構建為 docker image，可依實際環境選擇所要使用的雲端服務（如 K8S 或其它 Serverless 架構），透過此 docker image 部署 Container。

### 部署步驟

1. 從 **deploy** 資料夾將以下檔案複製到上一層目錄（即專案目錄下）：
	- Dockerfile。
	- docker-compose.yml。
	- docker-php-ext-opcache.ini。
	- php.ini-production。
2. 修改 **Dockerfile** 檔案中的 APP_VERSION 環境變數定義版號。
3. 修改 **docker-compose.yml** 檔案內容，將 target: production 取消註解。
4. 使用命令模式（PC 的 cmd 或 PowerShell，Mac 的終端機 - Terminal）前往專案目錄，輸入指令構建 docker image：

		docker-compose build --no-cache
		
5. 輸入指令確認所構建 docker image 內容的「"APP_VERSION=版號"」是否正確：

		docker inspect koa_api

6. 將構建好的 docker image 推送到雲端環境進行後續部署及設定環境變數，例如 GCP：

		docker tag koa_api asia-east1-docker.pkg.dev/sevensenses-team2-koa/petarush/petarush_api
		docker push asia-east1-docker.pkg.dev/sevensenses-team2-koa/petarush/petarush_api

7. 取得 **sql** 資料夾將以下檔案內容匯入到資料庫：
	- koa_log.sql。
	- koa_main.sql。
	- koa_static.sql。

8. 依據前述 2. 步驟定義的版號於 koa_main 資料庫的 ConfigVersions 資料表設定遊戲前後端相容版號。
9. 設定 [命令列程式](#cli) 的「 更新賽季、釋放比賽、賽季結算獎勵」排程。

### 環境變數

- SYSENV：指定系統環境名稱，通常用於開發時期，可針對開發期需求判斷此變數值建立特定功能。
	- 發行環境不需提供此環境變數。
	- local：本機環境，此環境才會紀錄 log 檔案。
	- developement：系統處於開發環境。
- PROCESS_TIMING：是否回傳處理計時，無此環境變數或變數值不為「字串 true」，代表不回傳，通常只有本機開發環境或 API 效能測試時才需有此環境變數。
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
- DB\_ELITE\_HOST：「菁英測試」資料庫主機位址***（已停用）***。
- DB\_ELITE\_PORT：「菁英測試」資料庫主機 Port***（已停用）***。
- DB\_ELITE\_USERNAME：「菁英測試」資料庫使用者名稱***（已停用）***。
- DB\_ELITE\_PASSWORD：「菁英測試」資料庫使用者密碼***（已停用）***。
- DB\_ELITE\_NAME：「菁英測試」資料庫名稱***（已停用）***。
- MEMCACHED\_HOST：Memcache Server 位址。
- MEMCACHED\_PORT：Memcache Server Port。
- SSO\_URI：SSO 登入的環境網址。
- SSO\_CLIEND\_ID：SSO 登入的環境 Client ID。
- SSO\_CLIEND\_SECRET：SSO 登入的註冊安全碼。
- APP\_URI：API 本身的環境網址，主要用於串接第三方（如 SSO 登入）時提供給對方的 Redirect URL。
- NFT\_URI：NFT 串接環境網址。
- NFT\_CLIENTID：NFT 串接環境的 Client ID。
- NFT\_APISECRET：NFT 串接環境的 API Secret。
- QuickSDK\_CallBackKey\_Android：QuickSDK儲值回調的Android平台簽證密鑰***（已停用）***
- QuickSDK\_CallBackKey\_iOS：QuickSDK儲值回調的iOS平台簽證密鑰***（已停用）***
- MYCARD\_SWITCH：MyCard 購買開關，「字串 true」代表啟用，當 iOS 送審時需設置為「字串 false」。
- MYCARD\_URI：MyCard 溝通的API。
- MYCARD\_ALLOWIP：允許 MyCard 平台進入查詢的IP，內容值為 IP陣列的 JSON 字串。
- MYCARD\_FACSERVICEID： MyCard 遊戲服務ID。
- MYCARD\_FACGAMEID： 與 MyCard 串接所定義的遊戲名稱(英文)。
- MYCARD\_FACGAMENAME： 與 MyCard 串接的遊戲名稱。
- MYCARD\_SANDBOXMODE：MyCard 沙盒測試開關，開啟沙盒模式時為「字串 true」，發行環境需設置為「字串 false」。
- MYCARD\_FACKEY：MyCard 簽證密鑰。
- GOOGLE\_APPLICATION\_CREDENTIALS: 內建Google憑證位置,json檔(變數名稱不能改)
- GOOGLE\_SERVICE\_ACCOUNT : 平台服務帳號,json檔
- GOOGLE\_APPNAME : App名稱
- GOOGLE\_APIKEY: Google APIKEY 密鑰
- GOOGLE\_PURCHASEKEY: 購買 Google public key 
- GOOGLE\_PACKAGENAME: 前端目前使用的包名

## <span id="cli">命令列程式</span>

於目錄 /src/cli 中，透過 php Games.php 運行 /src/classes/Games/CommandWorkers 的功能。

### 更新賽季

- 用途：建立新賽季資料。
- 使用時機：排程，每 30 分鐘執行一次。
- 指令：

		php /var/www/html/cli/Games.php ChangeQualifyingSeason

### 釋放比賽

- 用途：未知異常造成競賽無法進行，導致相關使用者狀態卡在競賽中，可將過期賽局釋放（結束），讓使用者脫離比賽，而能正常進行遊戲。
- 使用時機：排程，每 5 分鐘執行一次。
- 指令：

		php /var/www/html/cli/Games.php ReleaseRace
		
### 賽季結算獎勵

- 用途：當賽季結束時，結算賽季排名並發放獎勵。
- 使用時機：排程，每小時的 01 與 31 分各執行一次。
- 指令：

		php /var/www/html/cli/Games.php SeasonRankingReward
		
###  重建角色技能

- 用途：當企劃對技能設定資料異動太大（如異動部位技能），導致現有角色持有技能不符時，須透過此命令重建技能。
- 使用時機：關機維護時，手動執行。
- 指令：

		php /var/www/html/cli/Games.php RebuildPlayerSkills
		
### MycardRestore

- 用途：當 MyCard 無法與我方串接 API 導致未完成交易無法補儲時，可透過此命令主動處理。
- 使用時機：特殊情況才使用，通常於開發環境測試時設定排程每 5 分鐘執行一次。
- 指令：

		php /var/www/html/cli/Games.php MyCardRestore

## XDebug for Visual Studio Code

在 Container 內安裝 XDebug，使外部的 VS Code 編輯器可透過中斷點偵查程式運行獲取資訊。

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
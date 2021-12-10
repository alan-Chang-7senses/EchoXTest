# 30點競速遊戲 API

---

提供給 30 點競速遊戲前端 Unity 使用的後端邏輯與資料存取相關 API。

## 資料夾

### deploy - 環境部署檔案

 - __Dockerfile：__ 用來 build 執行環境檔案，內容同時包含版號以及 development 與 production 環境敘述。
 - __docker-compose.yml：__ 用於 build image 以及開發期部署環境用的 YAML 檔案，通常用於開發期，正式環境部署需要視需求更動。
 - __php.ini-development：__ 開發環境用的 php.ini。
 - __php.ini-production：__ 發行環境用的 php.ini。

### docs - API 說明文件資料夾

- [文件首頁](docs/index.md)。

### src - 程式原始碼檔案與資料夾

當有詢問請求進來時，將經由 .htaccess 敘述的 rewrite 導向 index.php 進行處理，或依據 .htaccess 敘述的例外直接執行。

- __logs：__ 系統運行的 log 存放區，通常為 error log。

## 環境部署

### 步驟

1. 將 **deploy** 資料夾中的檔案複製到上一層。
- 修改 **Dockerfile** 檔案中的 APP_VERSION 環境變數定義版號。
- 修改 docker-compose.yml 檔案內容：
	- 修改 ports。
	- 修改環境變數。
- 輸入指令構建 docker image：

		docker-compose build
		
-  輸入指令開起 Container：

		docker-compose up -d

### 環境變數

- SYSENV：指定系統環境名稱，通常用於開發時期，可針對開發期需求判斷此變數值建立特定功能，發行環境不需提供此環境變數。
	- developement：代表系統於開發環境。
- DB_LABEL：欲連接的預設資料庫標記名稱。
- DB_HOST：資料庫主機位址。
- DB_PORT：資料庫主機 Port。
- DB_USERNAME：資料庫使用者名稱。
- DB_PASSWORD：資料庫使用者密碼。
- DB_NAME：資料庫名稱。
- MEMCACHED_HOST：Memcache Server 位址。
- MEMCACHED_PORT：Memcache Server Port。

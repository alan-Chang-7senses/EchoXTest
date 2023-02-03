# EchoXTest
 - EchoX面試情境題之一
 - 程式碼實作部分在src/classes/Processors/BalanceTransfer.php中。
 

 ### 安裝軟體

- Docker（必要）：至 [Docker 網站](https://www.docker.com/) 依據本地作業系統下載並安裝適合的 Docker Desktop。
- Postman：用於測試 API。
- HeidiSQL：用於管理資料庫。

### 部署步驟


1. 使用命令模式（PC 的 cmd 或 PowerShell，Mac 的終端機 - Terminal）前往專案目錄，輸入指令構建 docker image：

		docker-compose build
		
2. 輸入指令開起 Container：

		docker-compose up -d
		
3. 使用 HeidiSQL 或 phpMyAdmin 等工具連接資料庫：
	- IP：127.0.0.1 或裝置本地 IP。
	- Port：37002。
	- Username：root。
	- Password：1111。
4. 取得 **sql** 資料夾將以下檔案內容匯入到資料庫：
echox_test.sql

- 使用 Postman 或任意瀏覽器調用BlanceTransfer API（不需任何參數），若回應以下資訊，即表示環境部署完成：  
<http://localhost:37001/BlanceTransfer>

		{
		    "error": {
		        "code": 26,
		        "message": "No parameter 'receiveUserId'"
		    }
		}
### 使用API詳見以下文件       
- [BlanceTransfer API文件](BalanceTransfer.md)
# github_server-php-sync
Php script for synchonizing an open github repo with your server folder and a js script automating this process

## HOW TO USE:

###### --- PHP ---
Put gitSync.php into your project folder. <br />
Open it and set the url variable on top, to the repo download url (on your repo page click the green code button, and then inspect the download zip button using the 'ctrl + shift + c' shortcut. Use the link on that button) <br />
Then set the unzippedFileName variable to whatever the name of the downloaded zip file will be. It's probably going to be 'your_repo_name-branch_name' e.g. github_server-php-sync-main. <br />
- To sync you have to load the page (the script from your browser).

###### --- JS ---
To make the synchronization automatic put gitSyncAuto.js into your project folder and include it (in the header) <br /> 
```html
<script src="gitSyncAuto.js"></script>
```
in every page that you don't immidietly get redirected from.
- This is activated by pressing f5 not the reload button.

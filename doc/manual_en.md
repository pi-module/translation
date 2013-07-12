## 1 Module Features</span>
* User: Developers or translators
* Usage: Translation module is a feature that provides you with the ability to extract variables with translation tag, translators can make csv files according to the source.
* Module functional constitute:
 * Set source path
 * Set source directory structure
 * Set search path
 * Set output path
 * Set output file name
 * Process
 * Preview the results
 * Download file

## 2 Operating steps</span>
**2.1 Enter the Translation Module**
 * Sign in to the admin platform
 * Select OPERATION>Translation, This will lead you to the Translation module

**2.2 Set source path**

Set source path is used to set the directory which need to be processed.Relative path mode is used to represent file path, user need to place the files that need to be translated in upload/translation/source/ directory beforehand, write permission for this directory is needed, user can upload using ftp or other means.

For example, place the following directory structure in upload/translation/source/.

![directory structure](https://f.cloud.github.com/assets/4251179/785981/f37bd486-ea98-11e2-930b-81a7100c39c3.png)

After user enters the path, system will automatically detect whether the path exists. If so, "Path is valid" will be displayed;

![path detect passed](https://f.cloud.github.com/assets/4251179/785987/f3e52b98-ea98-11e2-9b55-0348a53d36ab.png)

If not, "No path exists" will be displayed. Please reconfirm the correct path.

![path detect failed](https://f.cloud.github.com/assets/4251179/785984/f3e35e4e-ea98-11e2-9b46-b2243dff19df.png)

**2.3 Set source directory structure**

Select directory structure for the source path to filter unwanted search directory and generates the corresponding directories and files.

![set source directory structure](https://f.cloud.github.com/assets/4251179/785993/f4286282-ea98-11e2-9da8-8b6542d6c81c.png)

As for Pi / Module / Theme modes, system already set up corresponding filter directory list and the build directory format, also it can detect whether the source path user input corresponds to this format, if not prompt the user to select the correct mode.

![pop up notice](https://f.cloud.github.com/assets/4251179/785986/f3e351f6-ea98-11e2-804f-2eb8b4a794f0.png)

For example, /test corresponds to the mode Pi, /test/usr/module/article corresponds to the mode Module, /test/usr/theme/default corresponds to the mode Theme.

If you want to specify a directory to search, select Custom mode.

**2.4 Set search path**

Skip paths are directories and files under source path which do not need to be searched, different model type correspond to different skip path. Also, user can modify the filter directorys.

![set skip paths](https://f.cloud.github.com/assets/4251179/785991/f41a42f6-ea98-11e2-8fe7-28ec375e7b75.png)

User can also choose Custom mode to customize skip path. When user selected Custom mode, a directory tree will be displayed, just click on the directory which need to be searched.

![select file](https://f.cloud.github.com/assets/4251179/785989/f3eed2a6-ea98-11e2-8cf1-fdc4a9f54f06.png)

**2.5 Set output path**

Output path is the generated translation file's saving path, please notice that the folder can not contain already generated files, also write permission for this directory is needed.

![set output path](https://f.cloud.github.com/assets/4251179/785992/f41d62b0-ea98-11e2-83ae-2257155a6953.png)

**2.6 Set output file name**

When selected Custom mode, specify the output file name is allowed. You just need to input the file name, no suffix is needed, the system will defaultly output .csv file.

![set output file name](https://f.cloud.github.com/assets/4251179/785990/f41ae602-ea98-11e2-8b19-c854d8c1059b.png)

**2.7 Process**

Click the process button after you correctly complete all the above information, later results will be displayed in the bottom of the page.

**2.8 Process result**

Results generated as shown below, you can check files under paths or click the Download button to download files.

![process result](https://f.cloud.github.com/assets/4251179/785988/f3e2e054-ea98-11e2-89e5-59093736fb51.png)

**2.9 Download**

You can download csv files with the previously set structure by clicking the Download button, or click Return button to reset parameters. A generated structured directory with Pi mode as shown below.

![structured directory](https://f.cloud.github.com/assets/4251179/785994/f4526b18-ea98-11e2-9e28-7127b0e7bfac.png)

## 3 Translate file produce</span>
Open the generated csv file, replace row B with translated words and save the file. In this way, you produced a translate file.

**Notice**

1. Translated content need to be surrounded by half-angle quotes,  to prevent the systems do not recognize.

2. Set the encoding format as UTF-8.

![translate](https://f.cloud.github.com/assets/4251179/785995/f4532828-ea98-11e2-8942-f63cfb2ba07e.png)

## 4 Translate the web page</span>
After produce a translate file, place the translate file into the correspond locale folder. For example, place the Chinese translate file in /locale/zh-cn, Engilsh file in /locale/en. The file you download already contains the correspond directory structure, you just need to create corresponding language folder and place the translate file main.csv in it, then copy it into the system directory usr.

![locale folder](https://f.cloud.github.com/assets/4251179/785985/f3dfebb0-ea98-11e2-869c-90cee4582674.png)

![file](https://f.cloud.github.com/assets/4251179/785982/f3aba6c0-ea98-11e2-930c-21c4db5b6e74.png)

After placing the translate file into correct position, sign in to the admin platform, select SETTING>System, Change locale for application content. Do not forget to click the submit button at the bottom of the page.

![change language](https://f.cloud.github.com/assets/4251179/785980/f344679e-ea98-11e2-97e4-51a724488298.png)

At last, select OPERATION>System>Toolkit, flush cache for the changes to take effect.

![flush cache](https://f.cloud.github.com/assets/4251179/785983/f3b4ddda-ea98-11e2-82ce-dba5879c4cd4.png)

Thus completing the modification of the page language.

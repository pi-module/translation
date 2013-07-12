## 1 模块功能简介</span>
* 模块使用者：开发或翻译人员
* 模块用途：用于提取翻译标记中的语言变量，供翻译人员根据源语言制作csv文件
* 模块功能构成：
 * 指定源路径
 * 选择源路径目录结构
 * 选择检索路径
 * 指定输出路径
 * 指定输出文件名
 * 处理文件
 * 预览处理结果
 * 下载文件

## 2 操作步骤</span>
**2.1 进入翻译模块**
 * 登录管理员平台
 * 选择OPERATION>Translation，进入翻译模块

**2.2 指定源路径**

指定源路径用于设置需翻译的文件目录路径。目录路径采用相对路径的格式，用户需要提前将需要翻译的内容放在upload/translation/source/目录下，需要有upload/translation/source/写权限，可以采用ftp或者其他方式上传。

例如，将如下结构的目录放在upload/translation/source/下。

![directory structure](https://f.cloud.github.com/assets/4251179/785981/f37bd486-ea98-11e2-930b-81a7100c39c3.png)

用户输入路径后系统会自动检测路径是否存在，如果路径存在则会显示路径合法；

![path detect passed](https://f.cloud.github.com/assets/4251179/785987/f3e52b98-ea98-11e2-9b55-0348a53d36ab.png)

如果路径不存在，则显示路径不合法，请再次确认路径。

![path detect failed](https://f.cloud.github.com/assets/4251179/785984/f3e35e4e-ea98-11e2-9b46-b2243dff19df.png)

**2.3 选择源路径目录结构**

选择源路径对应的文件目录格式，以对应不同的过滤目录列表以及生成目录格式。

![set source directory structure](https://f.cloud.github.com/assets/4251179/785993/f4286282-ea98-11e2-9da8-8b6542d6c81c.png)

对于Pi/Module/Theme三种模式系统设定了相应的过滤目录列表和生成目录的格式，并且会检测用户输入的源路径是否对应于此种格式，如果不一致会提示用户选择正确的模式。

![pop up notice](https://f.cloud.github.com/assets/4251179/785986/f3e351f6-ea98-11e2-804f-2eb8b4a794f0.png)

例如Pi模式对应路径为/test，Module模式对应路径为/test/usr/module/article，Theme模式对应路径为/test/usr/theme/default。

如果希望自己指定需要检索的目录则可选择Custom模式。

**2.4 选择检索路径**

跳过路径是源路径下不需要检索的目录及文件列表，不同的读取模式有不同的过滤目录列表。用户可以自行修改过滤目录列表。

![set skip paths](https://f.cloud.github.com/assets/4251179/785991/f41a42f6-ea98-11e2-8fe7-28ec375e7b75.png)

用户也可以选择Custom模式自己选择需要检索的路径。当用户选择了Custom模式时，会出现一棵目录树，点击需要检索的目录即可。

![select file](https://f.cloud.github.com/assets/4251179/785989/f3eed2a6-ea98-11e2-8cf1-fdc4a9f54f06.png)

**2.5 指定输出路径**

输出路径是生成翻译文件的存放路径，注意输出路径中不能有已生成的文件，并且需要有该路径的写权限。

![set output path](https://f.cloud.github.com/assets/4251179/785992/f41d62b0-ea98-11e2-83ae-2257155a6953.png)

**2.6 指定输出文件名**

如果选择了Custom模式，则可以指定输出文件名。用户只需输入文件名即可，不需要带后缀，默认输出.csv文件。

![set output file name](https://f.cloud.github.com/assets/4251179/785990/f41ae602-ea98-11e2-8b19-c854d8c1059b.png)

**2.7 处理文件**

正确填写完以上所有信息之后点击处理按钮，稍后会在页面下方显示结果。

**2.8 生成结果**

生成的结果如下图所示，可以去显示的路径下查看文件或点击下载按钮下载文件。

![process result](https://f.cloud.github.com/assets/4251179/785988/f3e2e054-ea98-11e2-89e5-59093736fb51.png)

**2.9 下载文件**

点击下载按钮可下载带有预设结构目录的csv文件，或点击返回按钮重新设置参数。采用Pi模式生成的结构化文件目录如下图所示。

![structured directory](https://f.cloud.github.com/assets/4251179/785994/f4526b18-ea98-11e2-9e28-7127b0e7bfac.png)

## 3 制作翻译文件</span>
打开生成的csv文件如下图所示，将B列替换为翻译后的语言，保存文件。这样就完成了翻译文件的制作。

**注意**

1. 翻译后的内容需要加上半角的引号""，防止系统无法识别

2. 编码格式采用UTF-8

![translate](https://f.cloud.github.com/assets/4251179/785995/f4532828-ea98-11e2-8942-f63cfb2ba07e.png)

## 4 翻译网页</span>
制作好翻译文件之后，将翻译文件放到相应的locale文件夹下。例如，中文翻译文件放在/locale/zh-cn中，英文则放在/locale/en中。下载的文件中已经包含了相应的目录结构，只需要在locale文件夹中创建对应语言的文件夹然后将制作好的翻译文件main.csv正确放入文件夹后复制到系统的usr目录下就可以了。

![locale folder](https://f.cloud.github.com/assets/4251179/785985/f3dfebb0-ea98-11e2-869c-90cee4582674.png)

![file](https://f.cloud.github.com/assets/4251179/785982/f3aba6c0-ea98-11e2-930c-21c4db5b6e74.png)

将翻译文件放入正确位置之后，登录管理员平台，选择SETTING>System，修改应用程序的语言环境。修改之后不要忘记点击页面底部的提交按钮。

![change language](https://f.cloud.github.com/assets/4251179/785980/f344679e-ea98-11e2-97e4-51a724488298.png)

最后，选择OPERATION>System>Toolkit，清空缓存使修改生效。

![flush cache](https://f.cloud.github.com/assets/4251179/785983/f3b4ddda-ea98-11e2-82ce-dba5879c4cd4.png)

这样就完成了对页面语言的修改。

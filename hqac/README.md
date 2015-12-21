MoeFM High-Quality Audio Checker
====================

MoeFM HTML5 Project Subject

License: GPLv3

More about this: http://moefou.org/topic/1939

## 调用参数

### url

萌否电台的音频文件位址（无论音质，可不含 `http://` 前缀）。

Method: `GET`
Required: `true`

示例：
```
url=http://nyan.90g.org/9/f/6d/ab2941597177f67434cb0a007c35f759_320.mp3
url=nyan.90g.org/e/6/57/c6d3aab94862090b6517708c2bdea56f.mp3
```

### callback

JSONP 的返回函数名，若指定该参数，则会以 `fnCallback({...})` 的形式返回数据。

Method: `GET`
Required: `false`

示例：
```
callback=fnCallback
```

## 返回数据

若未指定 `callback` 参数，则返回数据为 JSON 格式，无论错误码如何均返回 `200 OK`。

示例（未指定 `callback`）：

```json
{ // 这是返回正常的数据
    "request": "http:\/\/nyan.90g.org\/9\/f\/6d\/ab2941597177f67434cb0a007c35f759_320.mp3", // 提交请求的 URL 地址
    "response": {
        "64":{ // 64Kbps 音质的数据，Object 数据格式下同
            "exist": 1, // 是否存在该码率文件，1 为存在，0 为不存在
            "url": "http:\/\/nyan.90g.org\/9\/f\/6d\/ab2941597177f67434cb0a007c35f759.mp3" // 该码率的理论文件位址（即使文件不存在）
        },
        "128":{ ... }, // 128Kbps 音质的数据
        "192":{ ... }, // 192Kbps 音质的数据
        "320":{ ... }, // 320Kbps 音质的数据
    },
    "error": 0 // 错误码，0 为返回正常，其他为返回出错
}
```

```json
{ // 这是返回异常的数据（目前仅有判断 URL 是否正常之功能）
    "request": "http://moefm.ccloli.com",
    "error": 1,
    "error_msg": "URL\\u0020\\u4e0d\\u5339\\u914d"
}
```


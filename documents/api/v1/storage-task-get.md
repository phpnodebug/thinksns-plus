# 储存资源获取

## 接口地址

```
/api/v1/storages/{storage}/{process?=100}
```

## 请求方式

```
GET
```

## 接口变量

| name     | must     | description |
|----------|:--------:|:--------:|
| storage  | yes      | 要获取的附件唯一标识 |
| process  | no       | 设置转换的图片相对质量 |

### process

直接设置转换值～转换范围为相对于原图质量的百分比，范围`1`-`100`的整数，如果书浮点数，将会直接转换为整数执行转换。

参数为可选，默认值100，返回源文件。

### HTTP Status Code

302

#### 转换支持文件拓展名

- png
- jpg
- jpeg
- webp

gif本身不支持裁剪～将不会对gif处理,并且云储存不受该操作影响。

## 获取说明

请求接口后，接口会发出302状态，重定向到真实资源地址，以此兼容云储存。


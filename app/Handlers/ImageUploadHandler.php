<?php

namespace App\Handlers;

use Image;

class ImageUploadHandler {

    // 只允许一下后缀名的图片文件上传
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    public function save($file, $folder, $file_prefix, $max_width = false) {
        // 构建存储文件夹规则
        $folder_name = "uploads/images/$folder/" . date('Ym', time()) . '/' . date('d', time()) . '/';

        // 设置文件具体存储路径
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        // 如果限制了图片宽带，就进行裁剪
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width) {
        // 先实例化，参数为文件的磁盘路径
        $image = Image::make($file_path);

        // 调整图片大小
        $image->resize($max_width, null, function($constraint) {
            // 设置宽度是$max_width, 高度是等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
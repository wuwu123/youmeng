<?php


namespace Youmeng\Request;


class UpdateRequest extends BaseRequest
{

    /**
     * 文件上传
     * @param string $content
     * @return array
     */
    public function update(string $content)
    {
        $this->requestModel->post('update', ['content' => $content]);
        return [$this->requestModel->isOk(), $this->requestModel->getData()];
    }

}
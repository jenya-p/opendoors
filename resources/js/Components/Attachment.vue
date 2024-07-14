<template>
    <div class="attachments compact">
        <div class="attachment" v-if="item">
            <a  :href="item.download_url" target="_blank">
                <span class="item-img">
                    <img v-if="item.thumb_url" :src="item.thumb_url" :alt="item.name"/>
                    <img v-else src="/images/file_types/default.svg" :alt="item.name"/>
                </span>
                <span class="item-name">{{ title ?? item.name }}</span>
            </a>
            <a @click="selectFilesToUpload" class="fa fa-refresh btn-refresh" title="Заменить изображения" target="_blank"></a>
            <a @click.stop="remove(item)" class="fa fa-times btn-remove" title="Удалить изображение"></a>
        </div>

        <a href="javascript:;" @click="selectFilesToUpload" class="attachment upload-button" v-else>
            <span class="item-img">
                <img src="/images/upload.svg" alt="Загрузить файлы...">
            </span>
            <span class="item-name">
                {{title ?? "загрузить..."}}
			</span>
        </a>
        <input type="file" name="files" ref="file_input" @change="uploadFiles" >
    </div>
</template>

<script>

export default {
    props: {
        item: {
            type: Object
        },
        item_type: '',
        item_id: 0,
        type: null,
        title: null
    },
    emits: ['update:item', 'uploaded'],
    methods: {
        remove(item) {
            var $v = this;
            axios.delete('/attachment/' + item.id)

                .then(function (response) {
                    $v.$emit('update:item', null);
                    $v.$emit('removed', item);
                })

                .catch(function (error) {
                    console.log(error, this);
                });
        },
        selectFilesToUpload() {
            this.$refs.file_input.click();
        },
        uploadFiles() {
            var $v = this;
            var fileInput = this.$refs.file_input;
            var url = fileInput.value;

            if (fileInput.files) {
                for (let filesKey in fileInput.files) {
                    if (typeof (fileInput.files[filesKey]) != 'object') {
                        continue;
                    }
                    var fileName = fileInput.files[filesKey].name;
                    if (fileName == undefined) {
                        continue;
                    }
                    var ext = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();

                    var data = new FormData();
                    data.append('file', fileInput.files[filesKey]);

                    let request = null;
                    if (this.item?.id){
                        request = axios.post('/attachment/' + this.item.id + '/replace', data);
                    } else {
                        data.append('item_type', this.item_type);
                        data.append('item_id', this.item_id ? this.item_id : '');
                        if(this.type){
                            data.append('type', this.type);
                        }
                        request = axios.post('/attachment', data)
                    }


                    request.then(function (response) {
                            if (response.status === 200) {
                                $v.$emit('update:item', response.data.item);
                                $v.$emit('uploaded', response.data.item);
                            }
                        })

                        .catch(function (error) {
                            var $v = this;
                            if (error.response) {
                                if (error.response.data && error.response.data.errors && typeof error.response.data.errors == 'object') {
                                    Object.keys(error.response.data.errors).forEach(function (fieldName) {
                                        console.log(fieldName + ': ' + error.response.data.errors[fieldName][0]);
                                    })
                                }
                            } else {
                                console.log(error, this);
                            }
                        });

                }

            } else {
                alert('Сюда может быть загружена только картинка');
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";
.attachments{

    input[type=file] {
        width: 0px;
        height: 0px;
        position: fixed;
        left: -100px;

    }

    display: flex;
    flex-wrap: wrap;
    gap: 15px;

    .attachment {
        width: 95px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        text-align: center;

        &:hover, a:hover {
            text-decoration: none;
        }

        .item-img {
            display: flex;
            @media screen and (min-width: 700px){
                min-width: 40px;
                height: 95px;
            }
            @media screen and (max-width: 700px){
                min-width: 80px;
                height: 95px;
            }
            border: 1px solid $shadow-color;
            border-radius: 4px;
            background-color: white;
            color: #67C3F2;
            position: relative;
            text-decoration: none;
            font-size: 12px;

            img {
                display: block;
                margin: auto;
                @media screen and (min-width: 700px){
                    min-width: 65px;
                    height: 70px;
                }
                @media screen and (max-width: 700px){
                    min-width: 80px;
                    height: 95px;
                }
            }
        }


        .btn-remove{
            margin: 0;
            border: 1px solid $shadow-color;
            position: absolute;
            background: white;
            padding: 0;
            width: 22px;
            height: 22px;
            line-height: 21px;
            text-align: center;
            border-radius: 50%;
            right: 10px;
            top: -11px;
            transition: color 200ms ease,background-color 200ms ease,border-color 200ms ease;
            &:hover{
                background-color: $attractive-color;
                border-color: $attractive-color;
                color: white;
            }
        }

        .btn-refresh{
            border: 1px solid $shadow-color;
            border-radius: 50%;
            margin: 0;
            position: absolute;
            background: white;
            padding: 0;
            width: 22px;
            height: 22px;
            line-height: 21px;
            text-align: center;
            border-radius: 50%;
            top: -11px;
            right: 40px;
            transition: color 200ms ease;
            overflow: visible;
            font-size: 0.8em;
            color: $light-fore-color;
            &:hover{
                background-color: #0c6e9b;
                border-color: #0c6e9b;
                color: white;
            }
        }

        .item-name {
            text-align: center;
            font-size: 14px;
            color: #333333;
            line-height: 17px;
            text-overflow: ellipsis;
            overflow: hidden;
            max-lines: 2;
            margin-top: 3px;
            display: -webkit-box;
            word-wrap: break-word;
            -webkit-line-clamp: 2; /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }
    }
    .upload-button {
        .item-img img{height: 35px; opacity: 0.8; transition: opacity 200ms ease}
        &:hover .item-img img{height: 35px; opacity: 1}
    }
}

</style>

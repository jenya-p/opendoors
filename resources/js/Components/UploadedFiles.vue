<template>
    <div :class="classes()">
        <slot name="item" v-for="item in items" v-bind:item="item">
            <a v-bind:href="item.download_url" class="ad__form-attachments__item" target="_blank">
                <span class="ad__form-attachments__item-name">{{item.name}}</span>
                <span class="ad__form-attachments__item-img">
                    <img v-if="item.thumb_url" v-bind:src="item.thumb_url" v-bind:alt="item.name" />
                    <img v-else src="/images/file_types/default.svg" v-bind:alt="item.name" />
                </span>
                <span class="ad__form-attachments__item-date" title="Дата загрузки">
                    {{item.created_at | date_format('dd.MM.yyyy HH:mm')}}
                </span>
                    <span href="javascript:;" @click.prevent.stop="remove(item)" class="ad__form-attachments__item-delete" title="Удалить">
                    <i class="fas fa-times-circle"></i><span>Удалить</span>
                </span>
            </a>
        </slot>

        <a href="javascript:;" @click="selectFilesToUpload" class="ad__form-attachments__item upload-button">
			<span class="ad__form-attachments__item-name">
				Загрузить файлы...
			</span>
            <span class="ad__form-attachments__item-img">
                <img src="/images/icons/upload.svg" alt="Загрузить файлы...">
            </span>
            <input type="file" name="files" ref="file_input" @change="uploadFiles" multiple>
        </a>
    </div>
</template>

<script>
	export default {
		name: "uploaded-files",
		data() {
			return {}
		},
		props: {
			items: {
				type: Array
			},
			item_type: '',
			item_id: 0,
			compact: {
				type: Boolean,
				default: false,
			}
		},
		methods: {
			remove(item) {
				var $v = this;
				axios.delete('/attachment/' + item.id)

					.then(function (response) {
						for (let itemsKey in $v.items) {
							if ($v.items[itemsKey].id == item.id) {
								$v.items.splice(itemsKey, 1)
                                $v.$emit('removed', item);
								return;
							}
						}
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
						data.append('item_type', this.item_type);
						data.append('item_id', this.item_id ? this.item_id : '');

						axios
							.post('/attachment', data)

							.then(function (response) {
								if (response.status === 200) {
									$v.items.push(response.data.item);
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
			},
			classes() {
				return ['ad__form-attachments', this.compact ? 'compact' : 'large'];
			}
		}
	}
</script>

<style>

</style>

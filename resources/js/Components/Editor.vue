<script>

// import Editor from 'ckeditor5-custom-build';

import {markRaw} from "vue";
import _debounce from "lodash/debounce";
const READONLY_ID = '123123321321';

export default {
    // components: {ckeditor: CKEditor.component},
    props: {
        item: Array,
        modelValue: {
            type: String,
            default: '',
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    emits: ['ready', 'update:modelValue', 'focus', 'blur', 'destroy'],
    methods: {
        focus() {
            this.instance.editing.view.focus();
        }
    },
    data() {

        return {
            instance: null,
            lastEditorData: null
        };

    },
    watch: {
        modelValue(value) {
            if (this.instance && value !== this.lastEditorData) {
                instance.data.set(value);
            }
        },
        disabled (readOnlyMode){
            if(this.instance){
                if ( readOnlyMode ) {
                    this.instance.enableReadOnlyMode( READONLY_ID );
                } else {
                    this.instance.disableReadOnlyMode( READONLY_ID );
                }
            }

        }
    },
    mounted() {

        let $v = this;

        let uploadUrl = '/attachment?_token=' + this.$page.props.csrf_token + '&mode=ckeditor';
        if ($v.item) {
            if (this.item.length > 0 && this.item[0] != null) {
                uploadUrl += '&item_type=' + this.item[0];
            }
            if (this.item.length > 1 && this.item[1] != null) {
                uploadUrl += '&item_id=' + this.item[1];
            }
            if (this.item.length > 2 && this.item[2] != null) {
                uploadUrl += '&type=' + this.item[2];
            }
        }

        let editorConfig = {
            width: "100%",
            simpleUpload: {
                uploadUrl: uploadUrl
            },
            // ...
            math: {
                engine: 'mathjax', // or katex or function. E.g. (equation, element, display) => { ... }
                lazyLoad: undefined, // async () => { ... }, called once before rendering first equation if engine doesn't exist. After resolving promise, plugin renders equations.
                outputType: 'script', // or span
                className: 'math-tex'
            },
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    '|',
                    //'selectAll',
                    '|',
                    'heading',
                    //'style',
                    '|',
                    //'fontSize',
                    //'fontFamily',
                    //'fontColor',
                    //'fontBackgroundColor',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    //'strikethrough',
                    'subscript',
                    'superscript',
                    // 'code',
                    'math',
                    // 'removeFormat',
                    '|',
                    //'horizontalLine',
                    'link',
                    'insertImage',
                    'insertTable',
                    // 'highlight',
                    'blockQuote',
                    //'codeBlock',
                    //'|',
                    'alignment',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'outdent',
                    'indent',
                    // '|',
                    // 'accessibilityHelp'
                ],
                shouldNotGroupWhenFull: false
            },
            fontFamily: {
                supportAllValues: true
            },
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            // htmlSupport: {
            //     allow: [
            //         {
            //             name: /^.*$/,
            //             styles: true,
            //             attributes: true,
            //             classes: true
            //         }
            //     ]
            // },
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'resizeImage'
                ]
            },

            language: 'ru',
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            placeholder: 'Введите или вставьте сюда ваш текст!',
            style: {
                definitions: [
                    {
                        name: 'Article category',
                        element: 'h3',
                        classes: ['category']
                    },
                    {
                        name: 'Title',
                        element: 'h2',
                        classes: ['document-title']
                    },
                    {
                        name: 'Subtitle',
                        element: 'h3',
                        classes: ['document-subtitle']
                    },
                    {
                        name: 'Info box',
                        element: 'p',
                        classes: ['info-box']
                    },
                    {
                        name: 'Side quote',
                        element: 'blockquote',
                        classes: ['side-quote']
                    },
                    {
                        name: 'Marker',
                        element: 'span',
                        classes: ['marker']
                    },
                    {
                        name: 'Spoiler',
                        element: 'span',
                        classes: ['spoiler']
                    },
                    {
                        name: 'Code (dark)',
                        element: 'pre',
                        classes: ['fancy-code', 'fancy-code-dark']
                    },
                    {
                        name: 'Code (bright)',
                        element: 'pre',
                        classes: ['fancy-code', 'fancy-code-bright']
                    }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            },

            // translations: [translations]
        };

        editorConfig.initialData = $v.modelValue;

        function setUpEditorEvents(editor) {
            const emitDebouncedInputEvent = _debounce((evt) => {

                // if ( props.disableTwoWayDataBinding ) {
                //     return;
                // }

                const data = $v.lastEditorData = editor.data.get();

                // The compatibility with the v-model and general Vue.js concept of input–like components.
                $v.$emit('update:modelValue', data, evt, editor);
            }, 300, {leading: true});

            editor.model.document.on('change:data', emitDebouncedInputEvent);

            editor.editing.view.document.on('focus', (evt) => {
                $v.$emit('focus', evt, editor);
            });

            editor.editing.view.document.on('blur', (evt) => {
                $v.$emit('blur', evt, editor);
            });
        }


        window.InlineEditor.create($v.$refs.element, editorConfig)
            .then(editor => {
                $v.instance = markRaw( editor );

                setUpEditorEvents( editor );

                if (editorConfig.initialData !== $v.modelValue) {
                    editor.data.set($v.modelValue);
                }


                // Set initial disabled state.
                if ($v.disabled) {
                    editor.enableReadOnlyMode('123123321321');
                }

                // Let the world know the editor is ready.
                $v.$emit('ready', editor);
            })
            .catch(error => {
                console.error(error);
            });
    },
    beforeUnmount() {
        if (this.instance) {
            this.instance.destroy();
            this.instance = null;
        }

        this.$emit('destroy');
    }
}

</script>

<template>
    <div ref="element"/>
</template>

<style scoped lang="scss">

</style>

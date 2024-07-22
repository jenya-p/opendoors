/**
 * @license Copyright (c) 2014-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import { CodeBlock } from '@ckeditor/ckeditor5-code-block';
import { SimpleUploadAdapter } from '@ckeditor/ckeditor5-upload';
import { HorizontalLine } from '@ckeditor/ckeditor5-horizontal-line';
import { Highlight } from '@ckeditor/ckeditor5-highlight';
import { RemoveFormat } from '@ckeditor/ckeditor5-remove-format';
import { InlineEditor } from '@ckeditor/ckeditor5-editor-inline';
import { Alignment } from '@ckeditor/ckeditor5-alignment';
import { Autoformat } from '@ckeditor/ckeditor5-autoformat';
import { Bold, Italic, Underline, Subscript, Superscript, Strikethrough, CodeEditing } from '@ckeditor/ckeditor5-basic-styles';
import { Style } from '@ckeditor/ckeditor5-style';
import { SelectAll } from '@ckeditor/ckeditor5-select-all';
import { BlockQuote } from '@ckeditor/ckeditor5-block-quote';
import { CloudServices } from '@ckeditor/ckeditor5-cloud-services';
import type { EditorConfig } from '@ckeditor/ckeditor5-core';
import { Essentials } from '@ckeditor/ckeditor5-essentials';
import { Heading } from '@ckeditor/ckeditor5-heading';
import { Image, ImageCaption, ImageStyle, ImageToolbar, ImageUpload, ImageResize } from '@ckeditor/ckeditor5-image';
import { Font, FontBackgroundColor, FontColor, FontFamily, FontSize } from '@ckeditor/ckeditor5-font';
import { Indent } from '@ckeditor/ckeditor5-indent';
import { Link } from '@ckeditor/ckeditor5-link';
import { List } from '@ckeditor/ckeditor5-list';
import { MediaEmbed } from '@ckeditor/ckeditor5-media-embed';
import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
import { PasteFromOffice } from '@ckeditor/ckeditor5-paste-from-office';
import { Table, TableToolbar } from '@ckeditor/ckeditor5-table';
import { TextTransformation } from '@ckeditor/ckeditor5-typing';
import { Undo } from '@ckeditor/ckeditor5-undo';
import { GeneralHtmlSupport } from '@ckeditor/ckeditor5-html-support';
import Math from '@isaul32/ckeditor5-math/src/math';
import AutoformatMath from '@isaul32/ckeditor5-math/src/autoformatmath';
declare class Editor extends InlineEditor {
    static builtinPlugins: (typeof Alignment | typeof Autoformat | typeof AutoformatMath | typeof BlockQuote | typeof Bold | typeof Underline | typeof Subscript | typeof Superscript | typeof CloudServices | typeof CodeBlock | typeof CodeEditing | typeof GeneralHtmlSupport | typeof Essentials | typeof Font | typeof FontBackgroundColor | typeof FontColor | typeof FontFamily | typeof FontSize | typeof Heading | typeof Highlight | typeof HorizontalLine | typeof Image | typeof ImageCaption | typeof ImageStyle | typeof ImageToolbar | typeof ImageUpload | typeof ImageResize | typeof Indent | typeof Italic | typeof Link | typeof List | typeof Math | typeof MediaEmbed | typeof Paragraph | typeof PasteFromOffice | typeof RemoveFormat | typeof SelectAll | typeof Strikethrough | typeof Style | typeof SimpleUploadAdapter | typeof Table | typeof TableToolbar | typeof TextTransformation | typeof Undo)[];
    static defaultConfig: EditorConfig;
}
export default Editor;

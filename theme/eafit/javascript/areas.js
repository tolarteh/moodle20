var areas = ["description", "introduction", "theory", "setup", "procedure"];
for (area_name in areas) {

    new TINY.editor.edit('editor_' + area_name ,{
	id:area_name,
	width:584,
        height:175,
	cssclass:'te',
	controlclass:'tecontrol',
	rowclass:'teheader',
	dividerclass:'tedivider',
	controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
		  'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
		  'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
		  'font','size','style','|','image','hr','link','unlink','|','cut','copy','paste','print'],
	footer:true,
	fonts:['Verdana','Arial','Georgia','Trebuchet MS'],
	xhtml:true,
	cssfile:'style.css',
	bodyid:'editor',
	footerclass:'tefooter',
	toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggle'},
	resize:{cssclass:'resize'}
    });


}
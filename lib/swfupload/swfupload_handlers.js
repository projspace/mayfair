function swfUploadLoaded() {
}

function fileDialogStart() {
}

function fileQueueError(file, errorCode, message)  {
	try {
		// Handle this error separately because we don't want to create a FileProgress element for it.
		switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
				alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
				return;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				alert("The file you selected is too big.");
				return;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				alert("The file you selected is empty.  Please select another file.");
				return;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
				alert("The file you choose is not an allowed file type.");
				return;
			default:
				alert("An error occurred in the upload. Try again later.");
				return;
		}
	} catch (e) {
	}
}

function fileQueued(file) {
	try {
		$('#'+this.customSettings.input).val(file.name);
		this.customSettings.file_queued = true;
	} catch (e) {
	}
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
}

function uploadStart(file) {
	try {
		$('#'+this.customSettings.progress_target).show();
		this.setButtonDisabled(true);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		$('#'+this.customSettings.progress_target+' .progress_bar').css('width', ($('#'+this.customSettings.progress_target).width()*percent)/100);
		$('#'+this.customSettings.progress_target+' .upload_message').html(percent+'%');
	} catch (e) {
	}
}

function uploadSuccess(file, serverData) {
	var ret = serverData.split(':');
	if(ret[0] == 'SUCCESS')
	{
		$('#'+this.customSettings.hidden_input).val(ret[1]);
		this.customSettings.upload_successful = true;
	}
	else
	{
		alert(ret[1]);
		this.customSettings.upload_successful = false;
	}
}

function uploadComplete(file) {
	if(!this.customSettings.upload_successful)
	{
		$('#'+this.customSettings.progress_target).hide();
		this.setButtonDisabled(false);
	}
	else
		$('#'+this.customSettings.form).submit();
}

function uploadError(file, errorCode, message) {
	try {
		
		if (errorCode === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
			// Don't show cancelled error boxes
			return;
		}
		
	// Handle this error separately because we don't want to create a FileProgress element for it.
		var error_msg = '';
		
		switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
				error_msg = "There was a configuration error.  You will not be able to upload a resume at this time.";
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
				error_msg = "You may only upload 1 file.";
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				break;
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
				error_msg = "The file could not be uploaded because of a server problem.";
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
				error_msg = "Upload Failed.";
				break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:
				error_msg = "Server (IO) Error";
				break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
				error_msg = "Security Error";
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				error_msg = "Upload Cancelled";
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				error_msg = "Upload Stopped";
				break;
			default:
				error_msg = "An error occurred in the upload. Try again later.";
				break;
		}
		alert(error_msg);
	} catch (ex) {
	}
}

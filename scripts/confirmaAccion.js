function confirmaAccion(pregunta) {
	lbdialog({
		 content: 'Go back to homepage?',
		 cancelButton: {
			 text: "No, mejor no"
		 },
		 OKButton: {
			 text: "Seguro"
			 callback: function() {
				 location.href = '#';
			 }
		 }
	});
}
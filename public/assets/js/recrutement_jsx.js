/**
 * Created by angeeric on 10/11/2016.
 */

// It may already be defined in metadata partial
if (typeof classes == "undefined") {
	var recrutements = {};
}

var SITE_URL = "/";


$(document).ready(function () {
	recrutements.options = {
		//$less: $('.less'),
		$loading: $(".ec-modal-loading"),

		init: function () {
			var open = false;

			$(document).on("submit", "form.form_recrut", function () {
				var span = recrutements.options.$loading.find("span");
				span.text("ENREGISTREMENT DE VOS DONNEES, patientez svp...");
				recrutements.options.$loading.css("display", "block");
			});

			$(document).on("submit", "form.form_recrut_small", function () {
				var span = recrutements.options.$loading.find("span");
				span.text("ENREGISTREMENT DE VOS DONNEES, patientez svp...");
				recrutements.options.$loading.css("display", "block");
			});

			/*
            $(document).on('change','select.drop_niveau_etude',function(){
                var id_niveau = parseInt($(this).val());
                var id_niveau_requis = parseInt($('form input[name=nivo_projet]').val());
                if(id_niveau <  id_niveau_requis){
                    $("div.actions ul li:nth-child(3)").addClass("disabled").attr("aria-disabled", "true");
                    alert('Impossible de poursuivre, votre niveau est inférieur au niveau requis')
                }
                else{
                    $("div.actions ul li:nth-child(3)").removeClass("disabled").removeAttr("aria-disabled");
                }
            })
            */

			//methode permettant de mettre dla photo dans le viewer
			$(document).on("change", "form.form_recrut #input_photo", function () {
				if (this.files && this.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
						$("form.form_recrut #view_photo").attr("src", e.target.result);
					};
					reader.readAsDataURL(this.files[0]);
				}
			});

			//methode permettant de mettre dla photo dans le viewer
			$(document).on(
				"change",
				"form.form_recrut_small #input_photo",
				function () {
					if (this.files && this.files[0]) {
						var reader = new FileReader();
						reader.onload = function (e) {
							$("form.form_recrut_small #view_photo").attr(
								"src",
								e.target.result
							);
						};
						reader.readAsDataURL(this.files[0]);
					}
				}
			);

			//methode qui permet de remplir le dropdown sous prefecture
			$(document).on("change", ".drop_projet", function (e) {
				e.preventDefault();
				var value = $(this).val();
				var name = $(this).attr("name");
				var input_design = $("input[name=design_projet]");
				var input_sigl = $("input[name=sigle_projet]");
				var input_dat_deb = $("input[name=date_deb_projet]");
				var input_dat_fin = $("input[name=date_fin_projet]");
				var nivo_projet = $("input[name=nivo_projet]");
				var label_diplome_requis = $("label#diplome_requis");
				if (value != 0) {
					if ((name = "choix_projet")) {
						recrutements.options.fillInputProjet(
							value,
							input_design,
							input_sigl,
							input_dat_deb,
							input_dat_fin,
							label_diplome_requis,
							nivo_projet
						);
					}
				} else {
					nivo_projet.val("0");
					input_design.val("");
					input_sigl.val("");
					input_dat_deb.val("");
					input_dat_fin.val("");
				}
			});

			//methode qui permet de remplir le dropdown sous prefecture
			$(document).on(
				"change",
				"form.form_recrut .drop_ssp, form.form_recrut_small .drop_ssp",
				function (e) {
					e.preventDefault();
					var value = $(this).val();
					var name = $(this).attr("name");
					var parent = $(this).parents("form");
					var input_lieu_residence = "";
					var input_lieu_residence2 = "";
					var input_lieu_residence3 = "";
					if (parent.hasClass("form_recrut")) {
						input_lieu_residence = $(
							"form.form_recrut input[name=lieuresidence]"
						);
						input_lieu_residence2 = $(
							"form.form_recrut input[name=lieuresidence2]"
						);
						input_lieu_residence3 = $(
							"form.form_recrut input[name=lieuresidence3]"
						);
					} else {
						input_lieu_residence = $(
							"form.form_recrut_small input[name=lieuresidence]"
						);
						input_lieu_residence2 = $(
							"form.form_recrut_small input[name=lieuresidence2]"
						);
						input_lieu_residence3 = $(
							"form.form_recrut_small input[name=lieuresidence3]"
						);
					}

					if ((name = "sousprefecture")) {
						input_lieu_residence.val("");
						input_lieu_residence.attr("disabled", "disabled");

						input_lieu_residence2.val("");
						input_lieu_residence2.attr("disabled", "disabled");

						input_lieu_residence3.val("");
						input_lieu_residence3.attr("disabled", "disabled");
					}

					if (value != 0 || value != "") {
						if ((name = "sousprefecture")) {
							input_lieu_residence.removeAttr("disabled");
							input_lieu_residence2.removeAttr("disabled");
							input_lieu_residence3.removeAttr("disabled");
							//recrutements.options.fillDropdown(value,select_sp,'getListSsp');
						}
					} else {
					}
				}
			);

			//methode qui permet de remplir le dropdown departement
			$(document).on(
				"change",
				".drop_departement,.drop_departement2,.drop_departement3,.drop_departement4",
				function (e) {
					e.preventDefault();
					var value = $(this).val();
					var name = $(this).attr("name");
					var parent = $(this).parents("form");

					var select_sp = "";
					var select_sp2 = "";
					var select_sp3 = "";
					var select_sp4 = "";

					if (parent.hasClass("form_recrut")) {
						select_sp = $("form.form_recrut select[name=sousprefecture]");
						select_sp2 = $("form.form_recrut select[name=sousprefecture2]");
						select_sp3 = $("form.form_recrut select[name=sousprefecture3]");
						select_sp4 = $("form.form_recrut select[name=sousprefecture4]");
					} else {
						select_sp = $("form.form_recrut_small select[name=sousprefecture]");
						select_sp2 = $(
							"form.form_recrut_small select[name=sousprefecture2]"
						);
						select_sp3 = $(
							"form.form_recrut_small select[name=sousprefecture3]"
						);
						select_sp4 = $(
							"form.form_recrut_small select[name=sousprefecture4]"
						);
					}

					if (name == "departement") {
						select_sp.empty();
						var $option2 = $("<option />");
						$option2
							.attr("value", "0")
							.text("Sélectionner une Sous Préfecture");
						select_sp.append($option2);
						select_sp.attr("disabled", "disabled");
					}

					if (name == "departement2") {
						select_sp2.empty();
						var $option2 = $("<option />");
						$option2
							.attr("value", "0")
							.text("Sélectionner une Sous Préfecture");
						select_sp2.append($option2);
						select_sp2.attr("disabled", "disabled");
					}

					if (name == "departement3") {
						select_sp3.empty();
						var $option2 = $("<option />");
						$option2
							.attr("value", "0")
							.text("Sélectionner une Sous Préfecture");
						select_sp3.append($option2);
						select_sp3.attr("disabled", "disabled");
					}

					if (name == "departement4") {
						select_sp4.empty();
						var $option2 = $("<option />");
						$option2
							.attr("value", "0")
							.text("Sélectionner une Sous Préfecture");
						select_sp4.append($option2);
						select_sp4.attr("disabled", "disabled");
					}

					if (value != 0 || value != "") {
						if (name == "departement") {
							select_sp.removeAttr("disabled");
							recrutements.options.fillDropdown(value, select_sp, "getListSsp");
						}
						if (name == "departement2") {
							select_sp2.removeAttr("disabled");
							recrutements.options.fillDropdown(
								value,
								select_sp2,
								"getListSsp"
							);
						}
						if (name == "departement3") {
							select_sp3.removeAttr("disabled");
							recrutements.options.fillDropdown(
								value,
								select_sp3,
								"getListSspAll"
							);
						}
						if (name == "departement4") {
							select_sp4.removeAttr("disabled");
							recrutements.options.fillDropdown(
								value,
								select_sp4,
								"getListSsp"
							);
						}
					} else {
					}
				}
			);

			//methode qui permet de remplir le dropdown region
			$(document).on("change", ".drop_region", function (e) {
				e.preventDefault();
				var value = $(this).val();
				var name = $(this).attr("name");
				var parent = $(this).parents("form");
				var select_dep = "";
				var select_sp = "";
				var input_lieu_residence = "";
				var input_lieu_residence2 = "";
				var input_lieu_residence3 = "";
				if (parent.hasClass("form_recrut")) {
					select_dep = $("form.form_recrut select[name=departement]");
					select_sp = $("form.form_recrut select[name=sousprefecture]");
					input_lieu_residence = $(
						"form.form_recrut input[name=lieuresidence]"
					);
					input_lieu_residence2 = $(
						"form.form_recrut input[name=lieuresidence2]"
					);
					input_lieu_residence3 = $(
						"form.form_recrut input[name=lieuresidence3]"
					);
				} else {
					select_dep = $("form.form_recrut_small select[name=departement]");
					select_sp = $("form.form_recrut_small select[name=sousprefecture]");
					input_lieu_residence = $(
						"form.form_recrut_small input[name=lieuresidence]"
					);
					input_lieu_residence2 = $(
						"form.form_recrut_small input[name=lieuresidence2]"
					);
					input_lieu_residence3 = $(
						"form.form_recrut_small input[name=lieuresidence3]"
					);
				}

				if ((name = "region")) {
					select_dep.empty();
					var $option1 = $("<option />");
					$option1.attr("value", "0").text("Sélectionner un Département");
					select_dep.append($option1);
					select_dep.attr("disabled", "disabled");

					select_sp.empty();
					var $option2 = $("<option />");
					$option2.attr("value", "0").text("Sélectionner une Sous Préfecture");
					select_sp.append($option2);
					select_sp.attr("disabled", "disabled");

					input_lieu_residence.val("");
					input_lieu_residence.attr("disabled", "disabled");

					input_lieu_residence2.val("");
					input_lieu_residence2.attr("disabled", "disabled");

					input_lieu_residence3.val("");
					input_lieu_residence3.attr("disabled", "disabled");
				}

				if (value != 0) {
					if ((name = "region")) {
						select_dep.removeAttr("disabled");
						recrutements.options.fillDropdown(
							value,
							select_dep,
							"getListDepartement"
						);
					}
				} else {
				}
			});

			//methode qui permet de remplir le dropdown district
			$(document).on("change", ".drop_district", function (e) {
				e.preventDefault();
				var value = $(this).val();
				var name = $(this).attr("name");
				var select_reg = $("select[name=region]");
				var select_dep = $("select[name=departement]");
				var select_sp = $("select[name=sousprefecture]");
				var input_lieu_residence = $("input[name=lieuresidence]");

				if ((name = "district")) {
					select_reg.empty();
					var $option = $("<option />");
					$option.attr("value", "0").text("Sélectionner une Région");
					select_reg.append($option);
					select_reg.attr("disabled", "disabled");

					select_dep.empty();
					var $option1 = $("<option />");
					$option1.attr("value", "0").text("Sélectionner un Département");
					select_dep.append($option1);
					select_dep.attr("disabled", "disabled");

					select_sp.empty();
					var $option2 = $("<option />");
					$option2.attr("value", "0").text("Sélectionner une Sous Préfecture");
					select_sp.append($option2);
					select_sp.attr("disabled", "disabled");

					input_lieu_residence.val("");
					input_lieu_residence.attr("disabled", "disabled");
				}

				if (value != 0) {
					if ((name = "district")) {
						select_reg.removeAttr("disabled");
						recrutements.options.fillDropdown(
							value,
							select_reg,
							"getListRegion"
						);
					}
				} else {
				}
			});
		},

		readURL: function (e, input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$("#view_photo").attr("src", e.target.result);
				};
				reader.readAsDataURL(input.files[0]);
			}
		},

		fillInputProjet: function (
			id,
			design,
			sigl,
			dat_deb,
			dat_end,
			label_diplome_requis,
			id_nivo_requis
		) {
			var post_url = SITE_URL + "recrutement.php";
			var $option = $("<option />");
			$.ajax({
				type: "POST",
				url: post_url,
				data: { id: id, project: "getProject" },
				beforeSend: function () {
					recrutements.options.$loading.css("display", "block");
				},
			})
				.done(function (data) {
					var obj = jQuery.parseJSON(data);
					if (obj.status == "ok") {
						design.val(obj.NomProjet);
						sigl.val(obj.SigleProjet);
						dat_deb.val(obj.Datedebut);
						dat_end.val(obj.Datefin);
						label_diplome_requis.text(obj.libelle_nivo_minimum);
						id_nivo_requis.val(obj.id_nivo_minimum);
						recrutements.options.fillDropDepartement(id, id_nivo_requis);
					} else if (data.status == "echoue") {
						//classes.options.show_error('Error Message', 'Probleme avec la base de donnée', true);
					} else {
						//$('#message').html(data.message);
					}
				})
				.fail(function (data) {
					//classes.options.show_error('Redirect Error',
					//    'There might be an error with the provided links, please contact the administrator.', true);
				})
				.always(function (data) {
					recrutements.options.$loading.css("display", "none");
				});
		},

		fillDropdown: function (id, selector, method) {
			var post_url = SITE_URL + "welcome/" + method;
			$.ajax({
				type: "POST",
				url: post_url,
				data: { id: id, method: method },
				beforeSend: function () {
					recrutements.options.$loading.css("display", "block");
				},
			})
				.done(function (data) {
					//alert(data);
					var obj = jQuery.parseJSON(data);
					if (obj.status == "ok") {
						//append to dropdown the new option
						$.each(obj.reps, function (key, value) {
							var $option = $("<option />");
							$option.attr("value", key).text(value);
							selector.append($option);
						});
						selector.trigger("liszt:updated");
					} else if (data.status == "echoue") {
						//classes.options.show_error('Error Message', 'Probleme avec la base de donnée', true);
					} else {
						//$('#message').html(data.message);
					}
				})
				.fail(function (data) {
					//classes.options.show_error('Redirect Error',
					//    'There might be an error with the provided links, please contact the administrator.', true);
				})
				.always(function (data) {
					recrutements.options.$loading.css("display", "none");
				});
		},

		fillDropDepartement: function (id, id_nivo_requis) {
			var post_url = SITE_URL + "recrutement.php";
			var dropd1 = $("select[name=choix_1]");
			var dropd2 = $("select[name=choix_2]");
			var dropd3 = $("select[name=choix_3]");
			dropd1.empty();
			dropd2.empty();
			dropd3.empty();
			$.ajax({
				type: "POST",
				url: post_url,
				data: { id: id, method: "getDepProjet" },
				beforeSend: function () {
					recrutements.options.$loading.css("display", "block");
				},
			})
				.done(function (data) {
					var obj = jQuery.parseJSON(data);
					if (obj.status == "ok") {
						//append to dropdown the new option
						$.each(obj.reps, function (key, value) {
							var $option = $("<option />");
							$option.attr("value", key).text(value);
							dropd1.append($option);
						});
						$.each(obj.reps, function (key, value) {
							var $option = $("<option />");
							$option.attr("value", key).text(value);
							dropd2.append($option);
						});
						$.each(obj.reps, function (key, value) {
							var $option = $("<option />");
							$option.attr("value", key).text(value);
							dropd3.append($option);
						});

						// dropd3.trigger("liszt:updated");
						//dropd2.trigger("liszt:updated");
						//dropd1.trigger("liszt:updated");
						var id_niveau_requis = parseInt(id_nivo_requis);
						var niv = $("form select.drop_niveau_etude").val();
						if (niv == "") {
							niv = 0;
						}
						if (niv != 0) {
							var id_niveau = parseInt(niv);
							if (id_niveau < id_niveau_requis) {
								$("form input[name=formSubmit]").attr("disabled", "disabled");
								alert(
									"Impossible de poursuivre, votre niveau est inférieur au niveau requis"
								);
							} else {
								$("form input[name=formSubmit]").removeAttr("disabled");
							}
						}
					} else if (data.status == "echoue") {
						//classes.options.show_error('Error Message', 'Probleme avec la base de donnée', true);
					} else {
						//$('#message').html(data.message);
					}
				})
				.fail(function (data) {
					//classes.options.show_error('Redirect Error',
					//    'There might be an error with the provided links, please contact the administrator.', true);
				})
				.always(function (data) {
					recrutements.options.$loading.css("display", "none");
				});
		},
	};

	recrutements.options.init();
});

					<div class="review-guidance-wrapper">
						<p class="review-guidance">Please check accuracy of the data and correct any mistakes you see.</p>
						<p class="review-guidance">If the data already looks correct then select <em>next image</em> to continue reviewing.</p>
					</div>

					<div class="project-data-entry-form" data-form-project-id="1">
						<form ng-submit="$parent.submitReview()">
							<input type="hidden" name="interface" value="loggedin" ng-init="$parent.entry.interface='<?php echo $interface; ?>'" ng-model="$parent.entry.interface">
							<input type="hidden" name="project_id" value="1" ng-init="$parent.entry.project_id=1" ng-model="$parent.entry.project_id">
							<input type="hidden" name="image_id" value="1" ng-init="$scope.entry.image_id=1" ng-model="$scope.entry.image_id">
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" ng-init="$parent.entry.user_id=<?php echo $user_id; ?>" ng-model="$parent.entry.user_id">
							<input type="hidden" name="request_time" value="<?php echo date('Y-m-d H:i:s'); ?>" ng-init="$parent.entry.request_time='<?php echo date('Y-m-d H:i:s'); ?>'" ng-model="$parent.entry.request_time">
							
							<div class="row">
								<div class="six columns">
									<label for="fossilGenus">Genus</label>
								  	<select class="u-full-width" id="fossilGenusSelect" ng-model="$parent.entry.genus">
								  		<option value="" selected>Please select genus</option>
										
										<optgroup label="Coral genera">	
											<option value="Acaciapora">Acaciapora</option>
											<option value="Acervularia">Acervularia</option>
											<option value="Actinopora">Actinopora</option>
											<option value="Alveolites">Alveolites</option>
											<option value="Alveopora">Alveopora</option>
											<option value="Amplexizaphrentis">Amplexizaphrentis</option>
											<option value="Amplexizaphrentis">Amplexus</option>
											<option value="Anabacia">Anabacia</option>
											<option value="Aspidiscus">Aspidiscus</option>
											<option value="Asteropora">Asteropora</option>
											<option value="Astrhelia">Astrhelia</option>
											<option value="Aulacophyllum">Aulacophyllum</option>
											<option value="Aulina">Aulina</option>
											<option value="Aulophyllum">Aulophyllum</option>
											<option value="Aulopora">Aulopora</option>
											<option value="Axogaster">Axogaster</option>
											<option value="Axophyllum">Axophyllum</option>
											<option value="Axopora">Axopora</option>
											<option value="Balanophylla">Balanophylla</option>
											<option value="Battersbyia">Battersbyia</option>
											<option value="Calamophyllia">Calamophyllia</option>
											<option value="Calceola">Calceola</option>
											<option value="Campophyllum">Campophyllum</option>
											<option value="Caninia">Caninia</option>
											<option value="Caryophyllia">Caryophyllia</option>
											<option value="Caulastrea">Caulastrea</option>
											<option value="Chonophyllum">Chonophyllum</option>
											<option value="Cladochonus">Cladochonus</option>
											<option value="Cladochonus">Cladochonus</option>
											<option value="Coenites">Coenites </option>
											<option value="Columnopora">Columnopora</option>
											<option value="Conularia">Conularia </option>
											<option value="Convexastraea">Convexastraea</option>
											<option value="Cosmoseris">Cosmoseris</option>
											<option value="Cryptangia">Cryptangia</option>
											<option value="Cyathophyllum">Cyathophyllum</option>
											<option value="Dendracis">Dendracis</option>
											<option value="Dibunophyllum">Dibunophyllum</option>
											<option value="Dictyoraea">Dictyoraea</option>
											<option value="Diphyphyllum">Diphyphyllum</option>
											<option value="Disphyllum">Disphyllum</option>
											<option value="Emmonsia">Emmonsia</option>
											<option value="Endophyllum">Endophyllum</option>
											<option value="Favia">Favia</option>
											<option value="Favosites">Favosites</option>
											<option value="Flabellum">Flabellum</option>
											<option value="Gonipora">Gonipora</option>
											<option value="Grewingkia">Grewingkia</option>
											<option value="Hadrophyllum">Hadrophyllum</option>
											<option value="Halisites">Halisites</option>
											<option value="Heliolites">Heliolites</option>
											<option value="Heliophyllum">Heliophyllum</option>
											<option value="Hexaphyllia">Hexaphyllia</option>
											<option value="Hydractinia">Hydractinia</option>
											<option value="Isastrea">Isastrea</option>
											<option value="Ketophyllum">Ketophyllum</option>
											<option value="Kodonophyllum">Kodonophyllum</option>
											<option value="Laotira">Laotira</option>
											<option value="Lindstroemia">Lindstroemia</option>
											<option value="Lithostrotion">Lithostrotion </option>
											<option value="Lonsdaleia">Lonsdaleia</option>
											<option value="Lophophyllum">Lophophyllum</option>
											<option value="Metriophyllum">Metriophyllum</option>
											<option value="Michelinia">Michelinia</option>
											<option value="Micrabacia">Micrabacia</option>
											<option value="Monastrea">Monastrea</option>
											<option value="Montlivaltia">Montlivaltia</option>
											<option value="Nemistrum">Nemistrum</option>
											<option value="Nemistrum">Nemistrum</option>
											<option value="Palaeosmilia">Palaeosmilia </option>
											<option value="Palastrea">Palastrea</option>
											<option value="Paleocyclus">Paleocyclus</option>
											<option value="Parasmilia">Parasmilia</option>
											<option value="Parastriapora">Parastriapora</option>
											<option value="Petraia">Petraia</option>
											<option value="Phillipsastrea">Phillipsastrea</option>
											<option value="Plasmopora">Plasmopora</option>
											<option value="Protaraea">Protaraea</option>
											<option value="Romingeria">Romingeria</option>
											<option value="Siphonodendron">Siphonodendron</option>
											<option value="Siphonophyllia">Siphonophyllia</option>
											<option value="Stauria">Stauria</option>
											<option value="Strombodes">Strombodes</option>
											<option value="Stylophora">Stylophora</option>
											<option value="Syringaxon">Syringaxon</option>
											<option value="Syringopora">Syringopora</option>
											<option value="Thamnastrea">Thamnastrea</option>
											<option value="Thecia">Thecia</option>
											<option value="Theocosmilia">Theocosmilia</option>
											<option value="Trachocyathus">Trachocyathus</option>
											<option value="Trochoseris">Trochoseris</option>
											<option value="Trochosmilia">Trochosmilia</option>
											<option value="Tryplasma">Tryplasma</option>
											<option value="Vaughania">Vaughania</option>
											<option value="Zaphrentis">Zaphrentis</option>
										</optgroup>
										
										<optgroup label="Not listed">
								  			<option value="Not listed">Specified in text box below.</option>
								  		</optgroup>
								  		
								  		<optgroup label="Illegible">
								  			<option value="Illegible">Genus is illegible.</option>
								  		</optgroup>
								  		
								  		<optgroup label="Missing">
								  			<option value="Missing">Genus information isn't present.</option>
								  		</optgroup>
								  	</select>
								  	
								  	<label for="fossilGenusText">Genus not listed</label>
								  	<input class="u-full-width" placeholder="Add genus here if not available in the above list" id="fossilGenusText" type="text" ng-model="entry.genuscustom">
								</div>
								
								<div class="six columns">
									<label for="fossilSpecies">Species</label>
								  	<input class="u-full-width" placeholder="Species name" id="fossilSpecies" type="text" ng-model="$parent.entry.species">
								  	
									<label for="fossilCollector">Collector</label>
								  	<input class="u-full-width" placeholder="Collector name" id="fossilCollector" type="text" ng-model="$parent.entry.collector">
								</div>
							</div>
							
							<div class="row">
								<div class="six columns">
									<label for="fossilAge">Geological age</label>
								  	<select class="u-full-width" id="fossilAge" ng-model="$parent.entry.age">
								  		<option value="" selected>Please select geological age</option>
								  		
								  		<optgroup label="Geological ages">
											<option value="Quaternary">Quaternary</option>
											<option value="Pliocene">Pliocene</option>
											<option value="Miocene">Miocene</option>
											<option value="Oligocene">Oligocene</option>
											<option value="Eocene">Eocene</option>
											<option value="Paleocene">Paleocene</option>
											<option value="Creataceous, Upper">Creataceous, Upper </option>
											<option value="Cretaceous, Lower">Cretaceous, Lower</option>
											<option value="Cretaceous">Cretaceous</option>
											<option value="Jurassic, Upper">Jurassic, Upper</option>
											<option value="Jurassic, Middle">Jurassic, Middle</option>
											<option value="Jurassic, Lower (Lias)">Jurassic, Lower (Lias)</option>
											<option value="Jurassic">Jurassic</option>
											<option value="Triassic, Upper">Triassic, Upper</option>
											<option value="Triassic, Middle">Triassic, Middle</option>
											<option value="Triassic, lower">Triassic, lower</option>
											<option value="Triassic">Triassic</option>
											<option value="Permian">Permian</option>
											<option value="Carboniferous Upper (Coal Measeures)">Carboniferous Upper (Coal Measeures)</option>
											<option value="Carboniferous Lower (Limestone)">Carboniferous Lower (Limestone)</option>
											<option value="Carboniferous">Carboniferous</option>
											<option value="Devonian, Upper">Devonian, Upper</option>
											<option value="Devonian, Middle">Devonian, Middle</option>
											<option value="Devonian, Lower">Devonian, Lower</option>
											<option value="Devonian">Devonian</option>
											<option value="Silurian, Pridoli">Silurian, Pridoli</option>
											<option value="Silurian, Ludlow">Silurian, Ludlow</option>
											<option value="Silurian, Wenlock">Silurian, Wenlock</option>
											<option value="Silurian, Llandovery">Silurian, Llandovery</option>
											<option value="Silurian">Silurian</option>
											<option value="Ordovician, Upper">Ordovician, Upper</option>
											<option value="Ordovician, Middle">Ordovician, Middle</option>
											<option value="Ordovician, Lower">Ordovician, Lower</option>
											<option value="Ordovician">Ordovician</option>
											<option value="Cambrian">Cambrian</option>
											<option value="Precambrian">Precambrian</option>
										</optgroup>
										
										<optgroup label="Illegible">
								  			<option value="Illegible">Age is illegible.</option>
								  		</optgroup>
								  		
								  		<optgroup label="Missing">
								  			<option value="Missing">Age information isn't present.</option>
								  		</optgroup>
								  	</select>
								</div>
								
								<div class="six columns">
									<label for="fossilLocality">Country</label>
								  	<select class="u-full-width" id="fossilLocality" ng-model="$parent.entry.country">
								  		<option value="" selected>Please select country</option>
								  		
								  		<optgroup label="Common countries">
								  			<option value="United Kingdom">United Kingdom</option>
								  		</optgroup>
								  		
								  		<optgroup label="All countries">
											<option value="Afganistan">Afganistan</option>
											<option value="Albania">Albania</option>
											<option value="Algeria">Algeria</option>
											<option value="Andorra">Andorra</option>
											<option value="Angola">Angola</option>
											<option value="Antigua and Barbuda">Antigua and Barbuda</option>
											<option value="Argentina">Argentina</option>
											<option value="Armenia">Armenia</option>
											<option value="Australia">Australia</option>
											<option value="Austria">Austria</option>
											<option value="Austria">Austria</option>
											<option value="Bahamas">Bahamas</option>
											<option value="Bahrain">Bahrain</option>
											<option value="Bangladesh">Bangladesh</option>
											<option value="Barbados">Barbados</option>
											<option value="Belarus">Belarus</option>
											<option value="Belgium">Belgium</option>
											<option value="Belize">Belize</option>
											<option value="Benin">Benin</option>
											<option value="Bhutan">Bhutan</option>
											<option value="Bolivia">Bolivia</option>
											<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
											<option value="Botswana">Botswana</option>
											<option value="Brazil">Brazil</option>
											<option value="Brunei Darussalam">Brunei Darussalam</option>
											<option value="Bulgaria">Bulgaria</option>
											<option value="Burkina Faso">Burkina Faso</option>
											<option value="Burundi">Burundi</option>
											<option value="Cabo Verde">Cabo Verde</option>
											<option value="Cambodia">Cambodia</option>
											<option value="Cameroon">Cameroon</option>
											<option value="Canada">Canada</option>
											<option value="Central African Republic">Central African Republic</option>
											<option value="Chad">Chad</option>
											<option value="Chile">Chile</option>
											<option value="China">China</option>
											<option value="Colombia">Colombia</option>
											<option value="Comoros">Comoros</option>
											<option value="Congo">Congo</option>
											<option value="Costa Rica">Costa Rica</option>
											<option value="Côte d'Ivoire">Côte d'Ivoire</option>
											<option value="Croatia">Croatia</option>
											<option value="Cuba">Cuba</option>
											<option value="Cyprus">Cyprus</option>
											<option value="Czech Republic">Czech Republic</option>
											<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
											<option value="Denmark">Denmark</option>
											<option value="Djibouti">Djibouti</option>
											<option value="Dominica">Dominica</option>
											<option value="Dominican Republic">Dominican Republic</option>
											<option value="Ecuador">Ecuador</option>
											<option value="Egypt">Egypt</option>
											<option value="El Salvador">El Salvador</option>
											<option value="Equatorial Guinea">Equatorial Guinea</option>
											<option value="Eritrea">Eritrea</option>
											<option value="Estonia">Estonia</option>
											<option value="Ethiopia">Ethiopia</option>
											<option value="Fiji">Fiji</option>
											<option value="Finland">Finland</option>
											<option value="France">France</option>
											<option value="Gabon">Gabon</option>
											<option value="Gambia">Gambia</option>
											<option value="Georgia">Georgia</option>
											<option value="Germany">Germany</option>
											<option value="Ghana">Ghana</option>
											<option value="Greece">Greece</option>
											<option value="Grenada">Grenada</option>
											<option value="Guatemala">Guatemala</option>
											<option value="Guinea">Guinea</option>
											<option value="Guinea-Bissau">Guinea-Bissau</option>
											<option value="Guyana">Guyana</option>
											<option value="Haiti">Haiti</option>
											<option value="Honduras">Honduras</option>
											<option value="Hungary">Hungary</option>
											<option value="Iceland">Iceland</option>
											<option value="India">India</option>
											<option value="Indonesia">Indonesia</option>
											<option value="Iran">Iran</option>
											<option value="Iraq">Iraq</option>
											<option value="Ireland">Ireland</option>
											<option value="Israel">Israel</option>
											<option value="Italy">Italy</option>
											<option value="Jamaica">Jamaica</option>
											<option value="Japan">Japan</option>
											<option value="Jordan">Jordan</option>
											<option value="Kazakhstan">Kazakhstan</option>
											<option value="Kenya">Kenya</option>
											<option value="Kiribati">Kiribati</option>
											<option value="Kuwait">Kuwait</option>
											<option value="Kyrgyzstan">Kyrgyzstan</option>
											<option value="Lao">Lao</option>
											<option value="Latvia">Latvia</option>
											<option value="Lebanon">Lebanon</option>
											<option value="Lesotho">Lesotho</option>
											<option value="Liberia">Liberia</option>
											<option value="Libya">Libya</option>
											<option value="Liechtenstein">Liechtenstein</option>
											<option value="Lithuania">Lithuania</option>
											<option value="Luxembourg">Luxembourg</option>
											<option value="Madagascar">Madagascar</option>
											<option value="Malawi">Malawi</option>
											<option value="Malaysia">Malaysia</option>
											<option value="Maldives">Maldives</option>
											<option value="Mali">Mali</option>
											<option value="Malta">Malta</option>
											<option value="Marshall Islands">Marshall Islands</option>
											<option value="Mauritania">Mauritania</option>
											<option value="Mauritius">Mauritius</option>
											<option value="Mexico">Mexico</option>
											<option value="Micronesia">Micronesia</option>
											<option value="Monaco">Monaco</option>
											<option value="Mongolia">Mongolia</option>
											<option value="Montenegro">Montenegro</option>
											<option value="Morocco">Morocco</option>
											<option value="Mozambique">Mozambique</option>
											<option value="Myanmar">Myanmar</option>
											<option value="Namibia">Namibia</option>
											<option value="Nauru">Nauru</option>
											<option value="Nepal">Nepal</option>
											<option value="Netherlands">Netherlands</option>
											<option value="New Zealand">New Zealand</option>
											<option value="Nicaragua">Nicaragua</option>
											<option value="Niger">Niger</option>
											<option value="Nigeria">Nigeria</option>
											<option value="North Korea">North Korea</option>
											<option value="Norway">Norway</option>
											<option value="Oman">Oman</option>
											<option value="Pakistan">Pakistan</option>
											<option value="Palau">Palau</option>
											<option value="Panama">Panama</option>
											<option value="Papua New Guinea">Papua New Guinea</option>
											<option value="Paraguay">Paraguay</option>
											<option value="Peru">Peru</option>
											<option value="Philippines">Philippines</option>
											<option value="Poland">Poland</option>
											<option value="Portugal">Portugal</option>
											<option value="Qatar">Qatar</option>
											<option value="Republic of Moldova">Republic of Moldova</option>
											<option value="Romania">Romania</option>
											<option value="Russian Federation">Russian Federation</option>
											<option value="Rwanda">Rwanda</option>
											<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
											<option value="Saint Lucia">Saint Lucia</option>
											<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
											<option value="Samoa">Samoa</option>
											<option value="San Marino">San Marino</option>
											<option value="Sao Tome and Principe">Sao Tome and Principe</option>
											<option value="Saudi Arabia">Saudi Arabia</option>
											<option value="Senegal">Senegal</option>
											<option value="Serbia">Serbia</option>
											<option value="Seychelles">Seychelles</option>
											<option value="Sierra Leone">Sierra Leone</option>
											<option value="Singapore">Singapore</option>
											<option value="Slovakia">Slovakia</option>
											<option value="Slovenia">Slovenia</option>
											<option value="Solomon Islands">Solomon Islands</option>
											<option value="Somalia">Somalia</option>
											<option value="South Africa">South Africa</option>
											<option value="South Korea">South Korea</option>
											<option value="South Sudan">South Sudan</option>
											<option value="Spain">Spain</option>
											<option value="Sri Lanka">Sri Lanka</option>
											<option value="Sudan">Sudan</option>
											<option value="Suriname">Suriname</option>
											<option value="Swaziland">Swaziland</option>
											<option value="Sweden">Sweden</option>
											<option value="Switzerland">Switzerland</option>
											<option value="Syrian Arab Republic">Syrian Arab Republic</option>
											<option value="Tajikistan">Tajikistan</option>
											<option value="Tanzania">Tanzania</option>
											<option value="Thailand">Thailand</option>
											<option value="The former Yugoslav Republic of Macedonia">The former Yugoslav Republic of Macedonia</option>
											<option value="Timor-Leste">Timor-Leste</option>
											<option value="Togo">Togo</option>
											<option value="Tonga">Tonga</option>
											<option value="Trinidad and Tobago">Trinidad and Tobago</option>
											<option value="Tunisia">Tunisia</option>
											<option value="Turkey">Turkey</option>
											<option value="Turkmenistan">Turkmenistan</option>
											<option value="Tuvalu">Tuvalu</option>
											<option value="Uganda">Uganda</option>
											<option value="Ukraine">Ukraine</option>
											<option value="United Arab Emirates">United Arab Emirates</option>
											<option value="United Kingdom">United Kingdom</option>
											<option value="United States of America">United States of America</option>
											<option value="Uruguay">Uruguay</option>
											<option value="Uzbekistan">Uzbekistan</option>
											<option value="Vanuatu">Vanuatu</option>
											<option value="Venezuela">Venezuela</option>
											<option value="Viet Nam">Viet Nam</option>
											<option value="Yemen">Yemen</option>
											<option value="Zambia">Zambia</option>
											<option value="Zimbabwe">Zimbabwe</option>
								  		</optgroup>

								  		<optgroup label="Not listed">
								  			<option value="Country not specified">Country not specified</option>
								  		</optgroup>
								  		
										<optgroup label="Illegible">
								  			<option value="Illegible">Country is illegible.</option>
								  		</optgroup>
								  		
								  		<optgroup label="Missing">
								  			<option value="Missing">Country information isn't present.</option>
								  		</optgroup>
								  	</select>
								</div>
							</div>
							
							<div class="row">
								<div class="six columns">
									<p>Check as many fields as possible before submitting the form. Some information may be missing.</p>
								</div>
								
								<div class="six columns">
									<label for="fossilPlace">Place</label>
									<input class="u-full-width" placeholder="Place" id="fossilPlace" type="text" ng-model="$parent.entry.place">
								</div>
							</div>
							
							<div class="row">
								<div class="form-button-wrapper">
									<input type="submit" value="Update entry" class="pink-button">
									<input type="submit" value="Next image" class="grey-button review-next">
									<input type="reset" value="Reset form" class="grey-button" style="display: none;">
								</div>
							</div>
						</form>
					</div>
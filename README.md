# README #



### What is this repository for? ###

This is the repositry for Storagenode package for Synology
Version 0.0


### How do I get set up? ###

* Summary of set up
	*	The setup required the SDK environment as suggested by Synology. https://github.com/SynologyOpenSource/pkgscripts-ng
	

* Configuration 
	*	One should run the set on a Linux Machine as the environment is not suported for windows

* Dependencies 	
	* Python 3.x
	
* Configuration 
	*	One should run the set on a Linux Machine as the environment is not suported for windows

* Dependencies 	
	* Python 3.x
	
	
	
 ### How to build the package in Windows? ###
 * Summary of set up
	*	Gets MODS packager from https://github.com/vletroye/Mods
	*	Install the MODS
	* 	Copy the contents of the folder WindowsTools in the the project folder.
	*	Run the MODS packager and open the project
	* 	Make changes in the files and 'build' from MODS
	


 

### Deployment instructions ###
In order to deploy the package you need to have a Synology NAS. have to first create the package using the developement set up and then test on the NAS by manually installing the package through the manual install.
Please copy the signed identity folder after generating the identity on your machine to Synology NAS. Please note the path of that folder. It will be required for configuration the Storagenode.


Folder name 'Package' contains all the UI files and the scripts that this package installs.
The folder name 'scripts' in the root folder contains all the install, post-install, start-stop, preuninstall, post-uninstall script.

*	Installation folder
	* /volume1/@appstore/StorJ
	
	
### Running the storage node

You may install the storagenode plugin by going to the package manager and installing the application manually. On execution you will the application UI will have respective fields to enter and validate the details
The start Storagenode button will only get activated once all fields are filled and validated.

*	Identity
	*	Identity needs to be created on another machine and the whole folder needs to be copied to the NAS.
	
*	Other Fields
Other fields are self explanatory.
	







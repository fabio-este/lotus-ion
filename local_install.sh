####################################
### DDEV config, launch, install ###
####################################
if [ -d ".ddev" ] 
then
    echo "ddev already seems to be configured. Please delete the .ddev directory if you want to re-configure ddev" 
else
    ddev config
    ddev start
    ddev composer install
fi

########################################
### Add Symlinks for easier handling ###
########################################

# Add assets symlink
if [[ -L _assets ]]; 
then
  echo "_assets symlink already exists"
else
    ln -s packages/site-package/Resources/Public _assets
fi

# Add templates symlink
if [[ -L _templates ]]; 
then
  echo "_templates symlink already exists"
else
    ln -s packages/site-package/Resources/Private _templates
fi

# Add bootstrap-package symlink
if [[ -L packages/bootstrap-package ]]; 
then
  echo "bootstrap-package symlink already exists"
else
    cd packages
    ln -s ../vendor/bk2k/bootstrap-package/ bootstrap-package
    cd ..
fi

#################################
### YARN installation & build ###
#################################

YARN_LOCK=packages/site-package/yarn.lock
if test -f "$YARN_LOCK"; 
then
    echo "Yarn seems to already be installed: $YARN_LOCK already exists. "
else
    cd assets
    yarn 
    yarn run build
    cd ..
fi

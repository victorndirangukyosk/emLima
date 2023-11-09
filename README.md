 ## Installation
 
 1) Clone the repository
 
 2) Change the database credentials in config.php
 
 3) Change the BASE_URL in define.php
 
 4) Create system/cache and system/log directories and give them full read/write access
 
 

####  Landing Page Development Workflow

Install dependencies `npm install`

Run `gulp` (gulp should be installed globally `npm install -g gulp`)

##### Please read through the CONTRIBUTING file for the git workflow and branching model

####  Folder Structure

-   Emlima                   // Contains all the application files
    -   db               // mydhuka database configuration
        -   mydhuka.sql     // migration scripts to create tables, views etc
                -   *           // Migration script files
    -   docker     // Migration scripts to create tables, views etc.. 
        -dockerfile 
    - assets              // Static resources like images, css, js etc
    - download        // Controller classes
    - front             // Model classes
        - controller           // Views
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
            - Affiliate                // Apis consumed by the mobile App
            - Aws            // Modules
            - Blog                // Apis consumed by the mobile App
            - Checkout            // Modules
            - Common                // Apis consumed by the mobile App
            - Deliverysystem            // Modules
            - emailtamplate                // Apis consumed by the mobile App
            - Errror            // Modules
            - Feed               // Apis consumed by the mobile App
            - Information            // Modules
            - Module                // Apis consumed by the mobile App
            - Pavblog            // Modules
            - Payment               // Apis consumed by the mobile App
            - PDF            // Modules
            - Product                // Apis consumed by the mobile App
            - Store            // Modules
        - event           // Views
            - App                // Apis consumed by the mobile App
            - Account            // Modules
        - Language           // Views
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
        - Model           // Model Class
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
            - Affiliate                // Apis consumed by the mobile App
            - Appearance            // Modules
            - Assets                // Apis consumed by the mobile App
            - Catalog            // Modules
            - Checkout                // Apis consumed by the mobile App
            - Common                // Modules
            - Design                // Modules
            - Discount                // Apis consumed by the mobile App
            - Drivers               // Modules
            - Executives           // Apis consumed by the mobile App
            - Extension                // Modules
            - Information
            - Localization
            - OrderProcessing group  //ModelOrderfor Order Processing    
            - Pavblog           // Modules
            - Payment                // ModelOrderfor Payment modes
            - Pezesha            //ModelOrderfor pezesha
            - Report               // Apis consumed by the mobile App
            - Sale           // Modules
            - Setting                // Apis consumed by the mobile App
            - Shipping            // Modules
            - Simple blog              // Apis consumed by the mobile App
            - tool            // Modules
            - total               // Apis consumed by the mobile App
            - user           // Modules
        - UI           // Views
            - Javascript                // Apis consumed by the mobile App
            - stylesheet            // Modules
            - Theme          // Views
    - Landing           // Views       
    - nbproject            
    - shopper           // Views
    - System
    - Upload
      - schedulertemp
      - br.sql
      - in.sql
      - index.html
    - vendors
      - Components
            - active
            - autocomplete
            - editor
            - fileext_textmode
            - filemanager
            - finder
            - install
            - market
            - poller
            - project
            - settings
            - update
            - user
            - worker_manager
      - js
            - amplify.min.js
            - instance.js
            - jquery-1.7.2.min.js
            - jquery-ui-1.8.23.custom.min.js
            - jquery.css3.min.js
            - jquery.easing.js
            - jquery.hoverIntent.min.js
            - jquery.scrollTo.js
            - jsend.js
            - localstorage.js
            - message.js
            - modal.js
            - sidebars.js
            - system.js
      - lib
      - bootstrap
                  - css
                        - bootstrap.css
                        - bootstrap.min.css
                  - fonts
      - languages
      - plugins       //The plugins directory is where you place plugins
      - themes
    - admin           // Admin panel static resources
         - controller           // Views
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
            - Affiliate                // Apis consumed by the mobile App
            - Aws            // Modules
            - Blog                // Apis consumed by the mobile App
            - Checkout            // Modules
            - Common                // Apis consumed by the mobile App
            - Deliverysystem            // Modules
            - emailtamplate                // Apis consumed by the mobile App
            - Errror            // Modules
            - Feed               // Apis consumed by the mobile App
            - Information            // Modules
            - Module                // Apis consumed by the mobile App
            - Pavblog            // Modules
            - Payment               // Apis consumed by the mobile App
            - PDF            // Modules
            - Product                // Apis consumed by the mobile App
            - Store            // Modules
        - event           // Views
            - App                // Apis consumed by the mobile App
            - Account            // Modules
        - Language           // Views
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
        - Model           // Model Class
            - Api                // Apis consumed by the mobile App
            - Account            // Modules
            - Affiliate                // Apis consumed by the mobile App
            - Appearance            // Modules
            - Assets                // Apis consumed by the mobile App
            - Catalog            // Modules
            - Checkout                // Apis consumed by the mobile App
            - Common                // Modules
            - Design                // Modules
            - Discount                // Apis consumed by the mobile App
            - Drivers               // Modules
            - Executives           // Apis consumed by the mobile App
            - Extension                // Modules
            - Information
            - Localization
            - OrderProcessing group  //ModelOrderfor Order Processing    
            - Pavblog           // Modules
            - Payment                // ModelOrderfor Payment modes
            - Pezesha            //ModelOrderfor pezesha
            - Report               // Apis consumed by the mobile App
            - Sale           // Modules
            - Setting                // Apis consumed by the mobile App
            - Shipping            // Modules
            - Simple blog              // Apis consumed by the mobile App
            - tool            // Modules
            - total               // Apis consumed by the mobile App
            - user           // Modules
        - UI           // Views
            - Javascript                // Apis consumed by the mobile App
            - stylesheet            // Modules
            - Amsify          // Views
            - AdminLTE                    // Modules
            - Bootstrap                   // Modules
            - FontAwesome                 // Modules
            - jQueryUI                    // Modules
            - JqueryUiIcons                // Modules

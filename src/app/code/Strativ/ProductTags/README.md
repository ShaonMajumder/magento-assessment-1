# Product Tags Module

This module provides functionality for managing product tags in the system. It includes both frontend and adminhtml components to create, view, and manage tags associated with products.

## Directory Structure

- **Block/**: Contains classes that define the presentation logic for tags.
  - **Adminhtml/**: Classes for the admin panel.
    - **Tag/**: Contains the Grid class for displaying tags in a grid format.
  - **Product/**: Classes for displaying product tags on the frontend.

- **Controller/**: Contains classes that handle requests and responses.
  - **Adminhtml/**: Classes for handling admin panel requests.
    - **Tag/**: Contains the Index class for the adminhtml tag index page.
  - **Tag/**: Contains the View class for viewing specific tags on the frontend.

- **Model/**: Contains classes that represent the data and business logic.
  - **ProductTags.php**: Represents the product tags model.
  - **ResourceModel/**: Classes for database interactions.
    - **ProductTags.php**: Resource model for product tags.
    - **Collection.php**: Manages a collection of product tags.
  - **Repository/**: Contains the ProductTagsRepository class for managing product tags.

- **Setup/**: Contains classes for setting up the module.
  - **InstallSchema.php**: Responsible for setting up the database schema.

- **Ui/**: Contains UI component classes.
  - **Component/**: Classes for UI components in the admin panel.
    - **Listing/**: Contains classes for listing components.
      - **Columns/**: Contains classes for defining columns in the listing.
  - **DataProvider/**: Classes for providing data to UI components.

- **view/**: Contains layout and template files for both adminhtml and frontend.
  - **adminhtml/**: Layout and UI component files for the admin panel.
  - **frontend/**: Layout, template, and CSS files for the frontend.

## Installation

1. Place the `ProductTags` directory in the `app/code/Strativ/` directory of your Magento installation.
2. Run the following commands to enable the module and set up the database schema:
   ```bash
   php bin/magento module:enable Strativ_ProductTags
   php bin/magento setup:upgrade
   ```

## Usage

- Admin users can manage tags through the admin panel.
- Customers can view product tags on the product pages.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for details.
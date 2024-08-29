# A plugin for the Canadian Covid Society's Clean Air in Schools campaign

## What it does
Takes data from an options page to generate a select element with provinces and territories as the options.
Outputs a mailto link that dynamically changes the email addresses, subject line, message, etc. based on the selected province/territory and specified context.

## How to use

1. There is an options page: CAiS Emails. Set up the data there.
2. Use the shortcode to output the select options and mailto link: [acf_province_email context="parents"]
Options are: parents | teachers 

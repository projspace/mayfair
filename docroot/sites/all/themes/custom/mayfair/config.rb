environment = :development

css_dir         = "css"
sass_dir        = "sass"
images_dir      = "images"
javascripts_dir = "js"
sourcemap       = true

output_style = (environment == :development) ? :expanded : :compressed

relative_assets = true
line_comments = false

sass_options = (environment == :development) ? {:debug_info => true} : {}

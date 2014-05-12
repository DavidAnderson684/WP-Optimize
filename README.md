# So Simple Theme

Looking for a simple, responsive, theme for your Jekyll powered blog? Well look no further. Here be **So Simple Theme**, the followup to [**Minimal Mistakes**](http://mmistakes.github.io/minimal-mistakes/) -- by designer slash illustrator [Michael Rose](http://mademistakes.com).

## So Simple Theme is all about:

* Responsive templates. Looking good on mobile, tablet, and desktop.
* Readable typography to make your words shine.
* Gracefully degrading in older browsers. Compatible with Internet Explorer 9+ and all modern browsers.
* Minimal embellishments and subtle animations. 
* Support for large images to call out your favorite posts.
* Disqus comments if you choose to enable.
* Tags for [Open Graph](https://developers.facebook.com/docs/opengraph/) and [Twitter Cards](https://dev.twitter.com/docs/cards) for a better social sharing experience.
* Vanilla [custom 404 page]({{ site.url }}/404.html) to get you started.
* Stylesheets for Pygments and Coderay [syntax highlighting](http://mmistakes.github.io/articles/so-simple-theme/code-highlighting-post/) to make your code examples look snazzy.
* Simple search that overlays results based on post title.
* Grunt build script for easier theme development.
* [Sitemap](https://github.com/mmistakes/so-simple-theme/blob/master/sitemap.xml) for search engines

![screenshot of So Simple Theme](http://mmistakes.github.io/so-simple-theme/images/so-simple-theme-preview.jpg)

General notes and suggestions for customizing So Simple Theme.

---

## Basic Setup for new Jekyll site

1. [Install Bundler](http://bundler.io) `gem install bundler` and then install [Jekyll](http://jekyllrb.com) and all dependencies `bundle install`.
2. Fork the [So Simple Theme repo](https://github.com/mmistakes/so-simple-theme/fork).
3. Clone the repo you just forked and rename it.
4. Edit `_config.yml` to personalize your site.
5. Check out the sample posts in `_posts` to see examples for pulling in large feature images, assigning categories and tags, and other YAML data.
6. Read the documentation below for further customization pointers and documentation.

[Demo the Theme](http://mmistakes.github.io/so-simple-theme/)

**Pro-tip:** Remove the sample posts in `_posts` and the `gh-pages` branch after cloning. There is a bunch of garbage in the `gh-pages` branch used for the theme's demo site.

---

## Setup for Existing Jekyll site

1. Clone the following folders: `_includes`, `_layouts`, `assets`, and `images`.
2. Clone the following files and personalize content as need: `about.md`, `articles.html`, `index.html`, `tags.html`, `feed.xml`, and `sitemap.xml`.
3. Set the following variables in your `config.yml` file:

``` yaml
title:            Site Title
description:      Site description for the metas.
logo:             site-logo.png
disqus_shortname: shortname
search:           true
# Your site's domain goes here. When working locally use localhost server leave blank
# PS. If you set this wrong stylesheets and scripts won't load and most links will break.
# PPS. If you leave it blank for local testing home links won't work, they'll be fine for live domains though.
url:              http://localhost:4000

# Owner/author information
owner:
  name:           Your Name
  avatar:         your-photo.jpg
  email:          your@email.com
  # Social networking links used in footer. Update and remove as you like.
  twitter:
  facebook:
  github:
  linkedin:
  instagram:
  tumblr:
  # For Google Authorship https://plus.google.com/authorship
  google_plus:    "http://plus.google.com/123123123123132123"

# Analytics and webmaster tools stuff goes here
google_analytics:
google_verify:
# https://ssl.bing.com/webmaster/configure/verify/ownership Option 2 content= goes here
bing_verify:

# Links to include in top navigation
# For external links add external: true
links:
  - title: About
    url: /about
  - title: Articles
    url: /articles
  - title: Google
    url: http://google.com
    external: true

# http://en.wikipedia.org/wiki/List_of_tz_database_time_zones
timezone:    America/New_York
pygments:    true
markdown:    kramdown

# https://github.com/mojombo/jekyll/wiki/Permalinks
permalink:   /:categories/:title/
```

---

## Folder Structure

``` bash
so-simple-theme/
├── _includes/
|    ├── browser-upgrade.html  #prompt to upgrade browser on < IE8
|    ├── footer.html  #site footer
|    ├── head.html  #site head
|    ├── navigation.html #site navigation and masthead
|    └── scripts.html  #jQuery, plugins, GA, etc.
├── _layouts/
|    ├── page.html  #page layout
|    └── post.html  #post layout
├── _posts/
├── assets/
|    ├── css/  #preprocessed less styles
|    ├── fonts/  #icon webfonts
|    ├── js/
|    |   ├── _main.js  #main JavaScript file, plugin settings, etc
|    |   ├── plugins  #jQuery plugins
|    |   └── vendor/  #jQuery and Modernizr
|    └── less/
├── images  #images for posts and pages
├── _config.yml  #Jekyll site options
├── about.md  #about page
├── articles.html  #lists all posts from latest to oldest
├── index.html  #homepage. lists 10 latest posts
├── tags.html  #lists all posts sorted by tag
└── sitemap.xml  #autogenerated sitemap for search engines
```

---

## Customization

For full customization details and more information on the theme check out the [So Simple theme setup guide](http://mmistakes.github.io/so-simple-theme/theme-setup/).

---

## Questions?

Having a problem getting something to work or want to know why I setup something in a certain way? Ping me on Twitter [@mmistakes](http://twitter.com/mmistakes) or [file a GitHub Issue](https://github.com/mmistakes/so-simple-theme/issues/new).

---

## License

This theme is free and open source software, distributed under the [GNU General Public License](LICENSE) version 2 or later. So feel free to to modify this theme to suit your needs. 

If you'd like to give me credit somewhere on your blog or tweet a shout out to [@mmistakes](https://twitter.com/mmistakes), that would be pretty sweet.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/mmistakes/so-simple-theme/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


=== json content builder ===

Contributors: Webnist
Tags: import
Requires at least: 4.4
Tested up to: 4.4.2
Version: 0.7.1
License: GPLv2 or later

json content builder

== Description ==

json content builder

post
`
{
	"name": "json builder",
	"type": "post",
	"content": {
		"Post01": {
			"name": "post01",
			"post_type": "post"
		},
		"Post02": {
			"name": "post02",
			"post_type": "post"
		},
		"Page01": {
			"name": "page01",
			"post_type": "page"
		},
		"Page02": {
			"name": "page02",
			"post_type": "page",
			"child": {
				"Page02 Child01": {
					"name": "page02-child-01",
					"post_type": "category"
				},
				"Page02 Child02": {
					"name": "page02-child-02",
					"post_type": "category"
				}
			}
		}
	}
}
`

term
`
{
	"name": "json builder",
	"type": "term",
	"content": {
		"Category01": {
			"slug": "category01",
			"taxonomy": "category"
		},
		"Category02": {
			"slug": "category02",
			"taxonomy": "category",
			"child": {
				"Category02 Child01": {
					"slug": "category02-child-01",
					"taxonomy": "category"
				},
				"Category02 Child02": {
					"slug": "category02-child-02",
					"taxonomy": "category"
				}
			}
		}
	}
}
`
= Contributors =

* [Webnist](https://profiles.wordpress.org/webnist)

== Installation ==

* A plug-in installation screen is displayed on the WordPress admin panel.
* It installs it in `wp-content/plugins`.
* The plug-in is made effective.
* Open \'json content builder\' menu.

== Screenshots ==

== Frequently Asked Questions ==

== Changelog ==

= 0.7.1 =
The first release.

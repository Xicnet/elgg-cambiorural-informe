<?php
/**
 * Register the ElggBlog class for the object/blog subtype
 */

if (get_subtype_id('object', 'informe')) {
	update_subtype('object', 'informe', 'ElggInforme');
} else {
	add_subtype('object', 'informe', 'ElggInforme');
}

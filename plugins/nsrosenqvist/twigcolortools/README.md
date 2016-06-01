Twig Color Tools is a plugin that registers a basic set of color functions that can be
useful in theme development.

# Usage

All functions can handle hex values + the 3 character shorthand, and rgb/rgba values.
The return format is the same as the one you sent in. The only exception is the
`color_mix` function that only returns as a hex value for simplicity's sake.

# Functions

Name | Parameters | Details | Description
-----|------------|---------|------------
`color_red` | *string* color [, *int* set] | color: Hex color or rgb(a) [, set: number 0-255] | If the second parameter isn't set we return the channel value of the color (0-255), if the second parameter is set we instead set that channel value to the color and return the new color.
`color_blue` | *string* color [, *int* set] | color: Hex color or rgb(a) [, set: number 0-255] | If the second parameter isn't set we return the channel value of the color (0-255), if the second parameter is set we instead set that channel value to the color and return the new color.
`color_green` | *string* color [, *int* set] | color: Hex color or rgb(a) [, set: number 0-255] | If the second parameter isn't set we return the channel value of the color (0-255), if the second parameter is set we instead set that channel value to the color and return the new color.
`color_alpha` | *string* color [, *int* set] | color: Hex color or rgb(a) [, set: percentage (0-100) ] | If the second parameter isn't set we return the alpha channel's value (0-1) as float, if the second parameter is set we instead set the alpha percentage to it.
`color_lighten` | *string* color, *int* percentage | color: Hex color or rgb(a), percentage: number (0-100) | Lighten the color by the set percentage.
`color_darken` | *string* color, *int* percentage | color: Hex color or rgb(a), percentage: number (0-100) | Darken the color by the set percentage.
`color_mix` | *array/string* color [, *string* color, ...] | Either an array of color strings or color strings as parameters | Mixes the colors and returns the average (red + blue = purple) as a hex string.

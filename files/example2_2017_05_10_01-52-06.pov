#include "/var/www/html/render-3d-master/files/example2_2017_05_10_01-52-06.pov-inc"
#include "/var/www/html/render-3d-master/src/Scenes/Pov/axes_macro.inc"
#include "math.inc"
#include "finish.inc"
#include "transforms.inc"
#include "colors.inc"


background {color rgb 1}

plane {
       <0,0,1>,-0.01
       pigment { color rgb <0.9,0.9,0.9> }
       finish { reflection 0.2 }
       }

light_source {
        <-10,-145,50> 
        rgb 1
}

light_source { <10,-150,75> color White }
light_source { <-110,-40,100> color White }

global_settings {
  assumed_gamma 2
}

camera {
	//orthographic
	sky <0,0,1>
	location <(10*-2),(12*-2),(10*1.2)>
	look_at <0,0,(10*0.45)>
	angle 35
	right -1.33*x
}



object {
  m_OpenSCAD_Model  Center_Trans(m_OpenSCAD_Model, x+y)
    Align_Trans(m_OpenSCAD_Model, -z, <0,0,0>)
      texture{ 
        pigment{ SlateBlue }
        finish { phong .51}
    }
}

// the coordinate grid and axes
Axes_Macro
(
	100,	// Axes_axesSize,	The distance from the origin to one of the grid's edges.	(float)
	10,	// Axes_majUnit,	The size of each large-unit square.	(float)
	10,	// Axes_minUnit,	The number of small-unit squares that make up a large-unit square.	(integer)
	0.003,	// Axes_thickRatio,	The thickness of the grid lines (as a factor of axesSize).	(float)
	off,	// Axes_aBool,		Turns the axes on/off. (boolian)
	off,	// Axes_mBool,		Turns the minor units on/off. (boolian)
	off,	// Axes_xBool,		Turns the plane perpendicular to the x-axis on/off.	(boolian)
	off,	// Axes_yBool,		Turns the plane perpendicular to the y-axis on/off.	(boolian)
	on	// Axes_zBool,		Turns the plane perpendicular to the z-axis on/off.	(boolian)
)

object
{
	Axes_Object
}

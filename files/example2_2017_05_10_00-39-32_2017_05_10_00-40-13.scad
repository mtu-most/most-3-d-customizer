cube_size = 20;// [10:Small,20:Medium,30:Large]

hole_diameter = 5;

hole_depth = 1;// [0,1,5,50]

show_wheels = yes;// [yes,no]

wheel_thickness = 1;// [1:40]

difference() {
  union() {
    translate([0, 0, cube_size/2]) cube([cube_size,cube_size,cube_size], center=true);
    if (show_wheels == "yes") {
      translate([0, 0, cube_size/2]) rotate([0, 90, 0]) {
        cylinder(r=cube_size/2, h=cube_size+(wheel_thickness*2), center=true);
      }
    }
  }
  translate([0, 0, cube_size-hole_depth]) cylinder(r=hole_radius, h=hole_depth);
}
        
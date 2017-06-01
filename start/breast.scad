//Two methods to measure for breast prosthetic either width of desired breast size (subsitute in b directly) or Length over top of breast l=b*(3/4)

// Using width of breast size or length over top of breast?
measure_choice = "width"; // [width,length]

// What is the value in mm?
value = 150;

// Flat or rounded back?
flat_or_rounded = "flat"; // [flat,rounded]

//ignore these
b = (measure_choice == "width" ? value : value*4/3);
w = b*2/3; //hiddens sphere
$fn = 100/1;

module breast(){
    difference(){
        hull(){
            sphere(w/1.75);//Sphere main
            translate([w/5,w/2,-w/8])sphere(w/3);//sphere left
            translate([w/5,-w/2,-w/8])sphere(w/3);//sphere right
            translate([0,0,w/2])sphere(w/5);//sphere top
        }
        if (flat_or_rounded == "flat") {
            translate([w,0,0])cube([w*2,w*2,w*2],center=true);//cleaving back with flat back
        }
        else {
            translate([w*5,0,0])sphere(w*5);//cleaving back with big sphere for round back
        }
    }
}

rotate([0,90,0])breast(); //print flat
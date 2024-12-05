import trimesh
import numpy as np
from trimesh.boolean import difference

# Create a sphere to represent the skull
sphere = trimesh.creation.icosphere(subdivisions=3, radius=1.0)

# Create eye sockets by subtracting smaller spheres
eye_socket_left = trimesh.creation.icosphere(subdivisions=2, radius=0.2)
eye_socket_left.apply_translation([-0.4, 0.4, 0.4])

eye_socket_right = trimesh.creation.icosphere(subdivisions=2, radius=0.2)
eye_socket_right.apply_translation([0.4, 0.4, 0.4])

# Create a mouth by subtracting a cylinder
mouth = trimesh.creation.cylinder(radius=0.3, height=0.2, sections=32)
mouth.apply_translation([0, -0.4, 0.8])

# Apply rotation to the mouth
rotation_matrix = trimesh.transformations.rotation_matrix(np.pi / 2, [1, 0, 0])
mouth.apply_transform(rotation_matrix)

# Combine all parts using boolean difference with OpenSCAD engine
skull = difference([sphere], [eye_socket_left, eye_socket_right, mouth], engine='scad')

# Check if the skull object is empty
if skull.is_empty:
    print("The skull object is empty after the difference operation.")
else:
    # Scale up the model
    skull.apply_scale(10.0)

    # Center the model
    skull.apply_translation(-skull.centroid)

    # Export the model to an STL file
    skull.export('skull_model.stl')
    print("STL file exported successfully.")
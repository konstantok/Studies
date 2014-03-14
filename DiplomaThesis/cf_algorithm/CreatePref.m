function [Pref]=CreatePref(TrainSet)
% 
% =================================
% 
% Univesrity of Patras 
% Computer Engineering and Informatics Department 
% 
% Diploma Thesis 
% Aimed Product Suggestion to Social Network Users 
% 
% Konstantinos Konstantopoulos kkonstanto@ceid.upatras.gr
% 
% =================================
%
    % Matrix Containing Preference Vectors w
    % Each Column contains the normalized Vector of the User

    [m n] = size(TrainSet);

    Pref = sparse(m,n);

    for i=1:n
        outdeg = sum(TrainSet(:,i));
        if outdeg
            Pref(:,i) = TrainSet(:,i)/outdeg;
        end
    end

end